<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Task;
use App\Services\FirebaseService;
use App\Models\{User,Role};
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{

    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
// $user = Auth::user();

// $firstTaskIds = DB::table('tasks as t1')
//     ->selectRaw('MIN(id) as id') 
//     ->groupBy('f_id')
//     ->pluck('id'); 

// $query = DB::table('tasks')
//     ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
//     ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
//     ->leftJoin('users', 'tasks.assign_by', '=', 'users.id')
//     ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
//     ->select(
//         'tasks.id',
//         'tasks.f_id',
//         'tasks.task_title',
//         'tasks.task_description',
//         'tasks.status',
//         'tasks.priority',
//         'tasks.start_date',
//         'tasks.end_date',
//         'categories.category',
//         'sub_categories.subcategory',
//         'users.name',
//         'roles.role_dept',
//         'roles.role'
//     );

// if ($user && !in_array($user->dept, ['Admin', 'HR'])) {
//     $query->whereIn('tasks.id', $firstTaskIds);
// }

// $task = $query->get();

$user = Auth::user();
$role = Role::find($user->role_id);
$store = DB::table('stores')->where('id', $user->store_id)->first();

// Get the first task IDs per f_id
$firstTaskIds = DB::table('tasks as t1')
    ->selectRaw('MIN(id) as id') 
    ->groupBy('f_id')
    ->pluck('id');

$query = DB::table('tasks')
    ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
    ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
    ->leftJoin('users as assigner', 'tasks.assign_by', '=', 'assigner.id') // Join users table for assign_by
    ->leftJoin('users as assignee', 'tasks.assign_to', '=', 'assignee.id') // Join users table for assign_to
    ->leftJoin('roles', 'assigner.role_id', '=', 'roles.id')
    ->select(
        'tasks.id',
        'tasks.f_id',
        'tasks.task_title',
        'tasks.task_description',
        'tasks.status',
        'tasks.priority',
        'tasks.start_date',
        'tasks.end_date',
        'categories.category',
        'sub_categories.subcategory',
        'assigner.name as assigner_name',
        'assignee.name as assignee_name',
        'roles.role_dept',
        'roles.role'
    );

// Apply store-based filtering for non-Admin, non-HR users
if (!in_array($user->dept, ['Admin', 'HR']) && $store) {
    $query->whereIn('tasks.id', $firstTaskIds)
          ->where('assignee.store_id', $store->id); // Filtering tasks by the assigned user's store
}

// Exclude the logged-in user's tasks (if needed)
$query->where('tasks.assign_to', '!=', $user->id);

$task = $query->get();

        // $task = DB::table('tasks')
        // ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
        // ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
        // ->leftJoin('users', 'tasks.assign_by', '=', 'users.id')
        // ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        // ->select(
        //     'tasks.*',
        //     'categories.category',
        //     'sub_categories.subcategory',
        //     'users.name',
        //     'roles.role_dept',
        //     'roles.role'
        // )
        // ->get();

        return view('task.list',['task'=>$task]);

    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $user = auth()->user();

    //     $role = Role::find($user->role_id);

    //     $assignedRoles = Role::join('role_based', 'roles.id', '=', 'role_based.assign_role_id')
    //         ->where('role_based.role_id', $role->id)
    //         ->select('roles.role_dept', 'roles.id', 'roles.role')
    //         ->groupBy('roles.role_dept', 'roles.id', 'roles.role')
    //         ->get();
            
    //     $store= DB::table('stores')->where('id',$user->store_id)->first();
        
       
    //     $cat=DB::table('categories')->where('status',1)->get();


    //     return view('task.add',['cat'=>$cat,'assignedRoles'=>$assignedRoles]);

    // }
    
    public function create()
{
    $user = auth()->user();
    $role = Role::find($user->role_id);
    $store = DB::table('stores')->where('id', $user->store_id)->first();
    
    $assignedRolesQuery = Role::join('role_based', 'roles.id', '=', 'role_based.assign_role_id')
        ->join('users', 'role_based.assign_role_id', '=', 'users.role_id')
        ->select('roles.role_dept', 'roles.id', 'roles.role')
        ->groupBy('roles.role_dept', 'roles.id', 'roles.role');
    
    if (!in_array($user->dept, ['Admin', 'HR'])) {
        if ($store) {
            $assignedRolesQuery->where('users.store_id', $store->id);
        }
    }
    
    $assignedRoles = $assignedRolesQuery->get();


    $cat = DB::table('categories')->where('status', 1)->get();

    return view('task.add', [
        'cat' => $cat,
        'assignedRoles' => $assignedRoles,
        'user'=>$user
    ]);
}


    public function getSubcategories(Request $request)
    {
        $subcategories = DB::table('sub_categories')
            ->where('cat_id', $request->category_id)
            ->where('status',1)
            ->get();

        return response()->json($subcategories);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'task_file' => 'nullable|file|max:5120|mimes:pdf,xlsx,xls,csv,jpg,jpeg,png,gif,doc,docx,txt'
    ]);

    $assignToArray = is_array($request->assign_to) ? $request->assign_to : [$request->assign_to];
    $assignBy = auth()->user()->id;
    $taskFilePath = null;

    // Handle file upload
    if ($request->hasFile('task_file')) {
        $file = $request->file('task_file');
        $fileName = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'assets/images/Task/';

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        $file->move($filePath, $fileName);
        $taskFilePath = $filePath . $fileName;
    }

    $notifications = [];

    foreach ($assignToArray as $assignTo) {
        $task = new Task();
        $task->task_title = $request->task_title;
        $task->category_id = $request->category_id;
        $task->subcategory_id = $request->subcategory_id;
        $task->assign_to = $assignTo;
        $task->task_description = $request->task_description;
        $task->additional_info = $request->additional_info;
        $task->start_date = $request->start_date;
        $task->start_time = $request->start_time;
        $task->end_date = $request->end_date;
        $task->end_time = $request->end_time;
        $task->priority = $request->priority;
        $task->task_file = $taskFilePath;
        $task->assign_by = $assignBy;

        $task->save();
        
        $task->f_id = $task->id;
        $task->save();

        try {
            $user = User::find($assignTo);

            if ($user && $user->device_token) {
                $taskTitle = $request->task_title;
                $taskBody = "You have been assigned a new task: " . $taskTitle;

                $response = app(FirebaseService::class)->sendNotification(
                    $user->device_token,
                    $taskTitle,
                    $taskBody
                );

                Notification::create([
                    'user_id' => $assignTo,
                    'noty_type' => 'task',
                    'type_id' => $task->id,
                ]);

                $notifications[] = [
                    'user_id' => $assignTo,
                    'device_token' => $user->device_token,
                    'title' => $taskTitle,
                    'body' => $taskBody,
                    'response' => $response
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Notification Send Error: ' . $e->getMessage());
            $notifications[] = [
                'user_id' => $assignTo,
                'error' => $e->getMessage()
            ];
        }
    }

    return redirect()->route('task.index')->with([
            'status' => 'success',
            'message' => 'Task Added successfully!'
        ]);
}

public function completedtaskstore(Request $request)
{
    $request->validate([
        'task_file' => 'nullable|file|max:5120|mimes:pdf,xlsx,xls,csv,jpg,jpeg,png,gif,doc,docx,txt'
    ]);

    $user_id = auth()->user()->id;
    $assignTo = $request->assign_to;

    $task = new Task();
    $task->f_id = $request->f_id;
    $task->task_title = $request->task_title;
    $task->assign_to = $assignTo;
    $task->category_id = $request->category_id;
    $task->subcategory_id = $request->subcategory_id;
    $task->task_description = $request->task_description;
    $task->start_date = $request->start_date;
    $task->start_time = $request->start_time;
    $task->end_date = $request->end_date;
    $task->end_time = $request->end_time;
    $task->priority = $request->priority;
    $task->assign_by = $user_id;

    // Handle file upload
    if ($request->hasFile('task_file')) {
        $file = $request->file('task_file');
        $fileName = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'assets/images/Task/';

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        $file->move($filePath, $fileName);
        $task->task_file = $filePath . $fileName;
    }

    try {
        $task->save(); 

        $user = User::find($assignTo);
        if ($user && $user->device_token) {
            $taskTitle = $request->task_title;
            $taskBody = "You have been assigned a new task: " . $taskTitle;

            $response = app(FirebaseService::class)->sendNotification(
                $user->device_token,
                $taskTitle,
                $taskBody
            );

            // Store notification
            $notification = Notification::create([
                            'user_id' => $assignTo,
                            'noty_type' => 'task',
                            'type_id' => $task->id,
                        ]);
        }
    } catch (\Exception $e) {
        \Log::error('Error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Task could not be saved.',
            'error' => $e->getMessage()
        ], 500);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Task added successfully!'
    ]);
}




    /**
     * Display the specified resource.
     */
   public function show($id)
{
    

    $taskCheck = DB::table('tasks')
        ->where('id', $id)
        ->select('id', 'f_id')
        ->first();

   

    $queryTaskId = is_null($taskCheck->f_id) ? $id : $taskCheck->f_id;
    
     $task = DB::table('tasks')
        ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
        ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
        ->leftJoin('users as assigned_to_user', 'tasks.assign_to', '=', 'assigned_to_user.id')
        ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
        ->leftJoin('roles as assigned_to_role', 'assigned_to_user.role_id', '=', 'assigned_to_role.id')
        ->leftJoin('roles as assigned_by_role', 'assigned_by_user.role_id', '=', 'assigned_by_role.id')
        ->where(function ($query) use ($queryTaskId) {
            $query->where('tasks.id', $queryTaskId)
                  ->orWhere('tasks.f_id', $queryTaskId);
        })
        ->select(
            'tasks.*',
            'categories.category',
            'sub_categories.subcategory',
            'assigned_to_user.name as assigned_to_name',
            'assigned_to_role.role as assigned_to_role',
            'assigned_by_user.name as assigned_by_name',
            'assigned_by_role.role as assigned_by_role'
        )
        ->orderBy('tasks.id', 'asc')
        ->get();

    return view('task.profile', ['task' => $task]);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('task.edit');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
