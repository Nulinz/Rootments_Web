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
use Illuminate\Support\Facades\Log;
use Exception;
use App\Http\Controllers\trait\common;


class TaskController extends Controller
{
    use common;

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
// $role = Role::find($user->role_id);
// $store = DB::table('stores')->where('id', $user->store_id)->first();

// // Get the first task IDs per f_id
// $firstTaskIds = DB::table('tasks as t1')
//     ->selectRaw('MIN(id) as id')
//     ->groupBy('f_id')
//     ->pluck('id');

// $query = DB::table('tasks')
//     ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
//     ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
//     ->leftJoin('users as assigner', 'tasks.assign_by', '=', 'assigner.id') // Join users table for assign_by
//     ->leftJoin('users as assignee', 'tasks.assign_to', '=', 'assignee.id') // Join users table for assign_to
//     ->leftJoin('roles', 'assigner.role_id', '=', 'roles.id')
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
//         'assigner.name as assigner_name',
//         'assignee.name as assignee_name',
//         'roles.role_dept',
//         'roles.role'
//     );

// // Apply store-based filtering for non-Admin, non-HR users
// if (!in_array($user->dept, ['Admin', 'HR']) && $store) {
//     $query->whereIn('tasks.id', $firstTaskIds)
//           ->where('assignee.store_id', $store->id); // Filtering tasks by the assigned user's store
// }

// // Exclude the logged-in user's tasks (if needed)
// $query->where('tasks.assign_to', '!=', $user->id);

// $task = $query->get();

        // $cluster_check = DB::table('m_cluster')
        // ->where('cl_name','=',$user->id)
        // ->count();

        // return $cluster_check;

        $task_cby = DB::table('tasks')->where('assign_by',$user->id)
        ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
        ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
        ->orderBy('id','DESC')
        ->select(
            'tasks.id',
            'tasks.task_title',
            'categories.category',
            'sub_categories.subcategory',
            'tasks.priority',
            'tasks.start_date',
            'tasks.end_date',
             )
        ->get();

        //   dd($task_cby->toArray());

     return view('task.list',['task'=>$task_cby,'r_id'=>$user->role_id]);

    }



    public function create(Request $req)
{


    // $hasCluster = $req->is('task-add/cluster');

    // if ($hasCluster) {
    //     $cluster=1;
    //  }

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

        // $user = Auth::user();

        // $employeesQuery = DB::table('users')->where('role_id',$user->id)->where('id', '!=', $user->id);

        // if (!in_array($user->dept, ['Admin', 'HR'])) {
        //     $employeesQuery->where('store_id', $user->store_id);
        // }

        // $employees = $employeesQuery->get();

        // return $employees;

    return view('task.add', [
        'cat' => $cat,
        'assignedRoles' => $assignedRoles,
        'user'=>$user,
        'employees'=>$employees ?? 0,
        'cluster'=>$cluster ?? 0
    ]);
}


public function create_task(Request $req)
{

    // $hasCluster = $req->is('task-add/hr');

    // if ($hasCluster) {
    //     $cluster=1;
    //  }

    $user = auth()->user();
    $r_id = $user->role_id;

    $cluster_check = DB::table('m_cluster as mc')
    ->leftJoin('users','users.id','=','mc.cl_name')
    ->where('mc.cl_name','=',$user->id)
    ->where('users.role_id',12)
    ->count();

    // $r_id = 12;
    // switch($r_id) {
    //     case 3:
    //     case 4:
    //     case 5:
    //         $arr = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    //         break;
    //     case 10:
    //         $arr = [11, 12];
    //         break;
    //     case 11:
    //         $arr = [12];
    //         break;
    //     case 12:
    //         if($cluster_check==0){
    //             $arr = [13, 14, 15, 16, 17, 18, 19];
    //         }else{
    //             $arr = [12 ,13, 14, 15, 16, 17, 18, 19];
    //         }

    //         break;
    //     case 13:
    //     case 14:
    //     case 15:
    //     case 16:
    //     case 17:
    //     case 18:
    //     case 19:
    //         $arr = range(12, 19);  // Array from 12 to 19
    //         $arr = array_diff($arr, [$r_id]); // Exclude the current role ID
    //         break;
    // }

    //  // $r_id = 12;
    //  switch($r_id) {
    //     case 3:
    //     case 4:
    //     case 5:
    //         $arr = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    //         break;
    //     case 7:
    //         $arr = [25];
    //         break;
    //     case 10:
    //         $arr = [11, 12];
    //         break;
    //     case 11:
    //         $arr = [12];
    //         break;
    //     case 12:
    //         if($cluster_check==0){
    //             $arr = [13, 14, 15, 16, 17, 18, 19];
    //         }else{
    //             $arr = [12 ,13, 14, 15, 16, 17, 18, 19];
    //         }

    //         break;
    //     case 13:
    //     case 14:
    //     case 15:
    //     case 16:
    //     case 17:
    //     case 18:
    //     case 19:
    //         $arr = range(12, 19);  // Array from 12 to 19
    //         $arr = array_diff($arr, [$r_id]); // Exclude the current role ID
    //         break;
    //     case 25:
    //         $arr = [7];
    //         break;
    //     case 30:
    //         $arr = [31,35,36];
    //         break;
    //     case 31:
    //     case 35:
    //     case 36:
    //         $arr = [30, 31, 35, 36];
    //         $arr = array_diff($arr, [$r_id]); // Exclude $r_id
    //         break;
    //     case 37:
    //         $arr = [32,38,39,40];
    //         break;
    //     case 38:
    //     case 39:
    //     case 40:
    //         $arr = [37, 38, 39, 40];
    //         $arr = array_diff($arr, [$r_id]); // Exclude $r_id
    //         break;
    //     case 41:
    //         $arr = [42];
    //         break;
    //     case 42:
    //         $arr = [41];
    //         break;
    // }


    $arr = $this->role_arr();


  $list =  DB::table('users')
    ->leftJoin('roles','roles.id','=','users.role_id')
    ->select('users.name','roles.role','roles.role_dept','users.id','users.store_id');


    if(($r_id >= 12 && $r_id <= 19)) {

        if($cluster_check==0){
            $list->where('users.store_id', $user->store_id)
            ->where('users.id', '!=', $user->id);
        }else{

            $list->leftJoin('stores', 'stores.id', '=', 'users.store_id')
            ->where(function($query) use ($user) {
                // Include all users with role_id = 12
                $query->where('users.role_id', 12)
                      // Include all users from the current user's store
                      ->orWhere('users.store_id', $user->store_id);
                    })
            ->where('users.id', '!=', $user->id)
            ->orderBy('users.role_id');


        }


    }

    else{
        $list->leftJoin('stores', 'stores.id', '=', 'users.store_id')
        ->select('users.name', 'roles.role', 'roles.role_dept', 'users.id', 'users.store_id', 'stores.store_name', 'stores.store_code') // Adjust store fields as needed
        ->whereIn('users.role_id', $arr)
        ->where('users.id', '!=', $user->id);

        // if($r_id==11){
        //     $list->orderBy('users.store_id')
        //             ->orderBy('users.role_id');
        // }else{
        //     $list->orderBy('users.role_id');
        // }

        $list->orderBy('users.role_id');

    }





    $list = $list->get();

    $cat = DB::table('categories')->where('status', 1)->get();


    //  return $list;

    return view('task.add1', [
        'cat' => $cat,
        'user'=>$list,

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
            Log::error('Notification Send Error: ' . $e->getMessage());
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

    //updating the old task



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


     $old_task = Task::find($request->task_id);
     $old_task->task_status = 'Assigned';
     $old_task->save();

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
        Log::error('Error: ' . $e->getMessage());
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

    public function completed_list(Request $req)
    {
        $user = Auth::user();

        $task_cby = DB::table('tasks')->where('assign_by',$user->id)
        ->where('task_status','Close')
        ->whereRaw('DATE_ADD(end_date, INTERVAL 15 DAY) >= ?', [now()])
        ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
        ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
        ->orderBy('id','DESC')
        ->select(
            'tasks.id',
            'tasks.task_title',
            'categories.category',
            'sub_categories.subcategory',
            'tasks.priority',
            'tasks.start_date',
            'tasks.end_date',
             )
        ->get();

        //   dd($task_cby);

        return view('task.completed_list',['task'=>$task_cby]);
    }


     public function updateTaskStatus(Request $request)
    {


        $request->validate([
            'id' => 'required|integer|exists:tasks,id',
            'status' => 'required|string',
        ]);

        $task = Task::findOrFail($request->id);

        if($request->status=='Close'){

            $first  = DB::table('tasks')->where('f_id',$task->f_id)->orderBy('id','asc')->first();

            if ($first) {
                // Update both the current task and the first task with the new status
                DB::table('tasks')
                    ->whereIn('id', [$task->id, $first->id]) // Updating both tasks
                    ->update(['task_status' => $request->status]);
            }

        }else{
             $task->update(['task_status' => $request->status]);
        }



        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully'
        ]);
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
