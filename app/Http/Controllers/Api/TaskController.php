<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseService;
use Illuminate\Support\Str;
use App\Models\Task;
use App\Models\User;
use App\Models\Role;
use App\Models\Leave;
use App\Models\Resignation;
use App\Models\Transfer;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
    public function index(Request $request)
    {


        $assignTo = $request->input('assign_to', Auth::user()->id);

        $statuses = ['To Do', 'In Progress', 'On Hold', 'Completed'];
        $tasks = [];

        foreach ($statuses as $status) {
            $tasks[$status] = DB::table('tasks')
                ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
                ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
                ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
                ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
                ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
                ->leftJoin('users as assigned_to_user', 'tasks.assign_to', '=', 'assigned_to_user.role_id')
                ->where('tasks.assign_to', $assignTo)
                ->where('tasks.task_status', $status)
                ->select(
                    'tasks.*',
                    'categories.category',
                    'sub_categories.subcategory',
                    'assigned_role.role as assigned_role',
                    'assigned_by_role.role as task_assigned',
                    'assigned_by_user.name as assigned_by',
                    'assigned_to_user.name as assigned_to'
                )
                ->orderBy('tasks.id', 'DESC')
                ->get();

            foreach ($tasks[$status] as $task) {
                $task->task_fileUrl = $task->task_file ? url('asset/images/Task/' . $task->task_file) : null;
            }


        }

        return response()->json([
            'data' => $tasks
        ]);
    }


    public function getCategories()
    {
        $categories = DB::table('categories')->select('id','category')->where('status',1)->get();

        return response()->json([
            'data' => $categories,
        ]);
    }

    public function getsubcategories(Request $request)
    {
        $subcategories = DB::table('sub_categories')
            ->where('cat_id', $request->category_id)
            ->select('id', 'subcategory')
            ->where('status', 1)
            ->get();

        return response()->json([
            'data' => $subcategories,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    

        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Fetch all roles, excluding the logged-in user's role if they belong to a store
        $rolesQuery = Role::select('id', 'role', 'role_dept');
        
        if (!is_null($user->store_id)) {
            $rolesQuery->where('id', '!=', $user->role_id);
        }
        
        $roles = $rolesQuery->get();
        
        // Fetch employees, excluding the logged-in user
        $employeesQuery = User::select('id', 'name', 'role_id', 'store_id')
            ->where('id', '!=', $user->id); // Exclude logged-in user
        
        if (!is_null($user->store_id)) {
            $employeesQuery->where('store_id', $user->store_id);
        }
        
        $employees = $employeesQuery->get()->groupBy('role_id');
        
        
        return response()->json([
            'data' => [
                'roles' => $roles,   // Filtered roles list
                'employees' => $employees, // Employees grouped by role (excluding logged-in user)
            ],
        ]);




    }


    /**
     * Store a newly created resource in storage.
     */
      public function store(Request $request)
    {
         $assignToArray = is_array($request->assign_to) ? $request->assign_to : [$request->assign_to];

        foreach ($assignToArray as $assignTo) {
            $task = new Task();
            $task->task_title = $request->task_title;
            $task->category_id = $request->category_id;
            $task->subcategory_id = $request->subcategory_id;
            $task->assign_to = $assignTo;
            $task->task_description = $request->task_description;
            $task->start_date = $request->start_date;
            $task->start_time = $request->start_time;
            $task->end_date = $request->end_date;
            $task->end_time = $request->end_time;
            $task->priority = $request->priority;

            if ($request->hasFile('task_file')) {
                $file = $request->file('task_file');
                $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
                $path = 'assets/images/Task/';
                $file->move($path, $name);

                $task->task_file = $path . $name;
            }

            $task->assign_by = $request->assign_by;
            $task->save();
            
            $task->f_id = $task->id;
            $task->save();

       $notifications = [];

        foreach ($assignToArray as $assignTo) {
            try {
                $user = User::findOrFail($assignTo);

                if ($user->device_token) {
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
                        'type_id' => $task->id
                    ]);

                    $notifications[] = [
                        'user_id' => $assignTo,
                        'device_token' => $user->device_token,
                        'title' => $taskTitle,
                        'body' => $taskBody,
                        'response' => $response
                    ];
                }
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $notifications[] = [
                    'user_id' => $assignTo,
                    'error' => 'User not found'
                ];
            }
        }



    }

    return response()->json([
        'success' => true,
        'message' => 'Task Added successfully'
    ]);
}

public function completedtaskstore(Request $request)
    {
        $user_id = auth()->user()->id;

        $assignToArray = is_array($request->assign_to) ? $request->assign_to : [$request->assign_to];

        foreach ($assignToArray as $assignTo) {
            $task = new Task();
            $task->f_id = $request->f_id;
            $task->task_title = $request->task_title;
            $task->task_title = $request->task_title;
            $task->category_id = $request->category_id;
            $task->subcategory_id = $request->subcategory_id;
            $task->assign_to = $assignTo;
            $task->task_description = $request->task_description;
            $task->start_date = $request->start_date;
            $task->start_time = $request->start_time;
            $task->end_date = $request->end_date;
            $task->end_time = $request->end_time;
            $task->priority = $request->priority;

            if ($request->hasFile('task_file')) {
                $file = $request->file('task_file');
                $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
                $path = 'assets/images/Task/';
                $file->move($path, $name);

                $task->task_file = $path . $name;
            }

            $task->assign_by = $user_id;
            $task->save();

       $notifications = [];

        foreach ($assignToArray as $assignTo) {
            try {
                $user = User::findOrFail($assignTo);

                if ($user->device_token) {
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
                        'type_id' => $task->id
                    ]);

                    $notifications[] = [
                        'user_id' => $assignTo,
                        'device_token' => $user->device_token,
                        'title' => $taskTitle,
                        'body' => $taskBody,
                        'response' => $response
                    ];
                }
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $notifications[] = [
                    'user_id' => $assignTo,
                    'error' => 'User not found'
                ];
            }
        }



    }

    return response()->json([
        'success' => true,
        'message' => 'Task Added successfully'
    ]);
}

  public function updateTaskStatus(Request $request)
    {

        $request->validate([
            'id' => 'required|integer|exists:tasks,id',
            'status' => 'required|string',
        ]);

        $task = Task::findOrFail($request->id);

        $task->update(['task_status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
     public function leavelist(Request $request)
     {
         $user_id = $request->input('user_id');

         $leave=DB::table('leaves')
        ->leftjoin('users','leaves.user_id','=','users.id')
        ->select('leaves.*','users.name','users.emp_code')
        ->where('leaves.created_by',$user_id)
        ->get();

        return response()->json([
            'data' => [
                'leave' => $leave
            ],
        ]);
     }

       public function reginationlist(Request $request)
     {
         $user_id = $request->input('user_id');

         $resgination = DB::table('resignations')
        ->leftjoin('stores','stores.id','=','resignations.store_id')
        ->leftjoin('users','users.id','=','resignations.emp_id')
        ->select('resignations.*','stores.store_name','users.emp_code')
        ->where('resignations.created_by',$user_id)
        ->get();

        return response()->json([
            'data' => [
                'resgination' => $resgination
            ],
        ]);
     }

      public function transferlist(Request $request)
     {
         $user_id = $request->input('user_id');

         $transfer = DB::table('transfers')
                    ->leftJoin('stores as from_stores', 'from_stores.id', '=', 'transfers.fromstore_id')
                    ->leftJoin('stores as to_stores', 'to_stores.id', '=', 'transfers.tostore_id')
                    ->leftJoin('users', 'users.id', '=', 'transfers.emp_id')
                    ->select(
                        'transfers.*',
                        'from_stores.store_name as from_store_name',
                        'to_stores.store_name as to_store_name',
                        'users.emp_code'
                    )
                    ->where('transfers.created_by',$user_id)
                    ->get();

        return response()->json([
            'data' => [
                'transfer' => $transfer
            ],
        ]);
     }

      public function leavestore(Request $request)
    {
          $user_id = auth()->user()->id;

          $role_get = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user_id)
            ->first();

        if ($role_get) {
            $leave = new Leave();
            $leave->start_date = $request->start_date;
            $leave->end_date = $request->end_date;
            $leave->request_type = $request->request_type;
            $leave->reason = $request->reason;
            $leave->start_time = $request->start_time;
            $leave->end_time = $request->end_time;
            $leave->user_id = $user_id;
            $leave->created_by = $user_id;

            if ($role_get->role == 'Store Manager') {
                $leave->request_to = 3;
            } elseif ($role_get->role == 'Manager') {
                $leave->request_to = 1;
            }
            elseif ($role_get->role == 'Managing Director') {
                $leave->request_to = 3;
            }
            else {
                $leave->request_to = 12;
            }

            $leave->save();
        } else {

            return response()->json(['error' => 'User role not found'], 404);
        }



        return response()->json([
            'success' => true,
            'message' => 'Leave Request Sent successfully'
        ]);
    }

     public function reginationstore(Request $request)
    {

         $user_id = auth()->user()->id;

          $role_get = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user_id)
            ->first();

            if ($role_get) {
                 $resgination = new Resignation();
                    $resgination->emp_id =$request->emp_id;
                    $resgination->emp_name =$request->emp_name;
                    $resgination->store_id =$request->store_id;
                    $resgination->res_date =$request->res_date;
                    $resgination->res_reason =$request->res_reason;
                    $resgination->created_by=$user_id;



               if ($role_get->role == 'Store Manager') {
                $resgination->request_to = 3;
            } elseif ($role_get->role == 'Manager') {
                $resgination->request_to = 1;
            }
            elseif ($role_get->role == 'Managing Director') {
                $resgination->request_to = 3;
            }
            else {
                $resgination->request_to = 12;
            }
                $resgination->save();
            } else {

                return response()->json(['error' => 'User role not found'], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Resign Request Sent successfully'
            ]);
    }

      public function transferstore(Request $request)
    {
        $user_id = auth()->user()->id;

          $role_get = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user_id)
            ->first();

             if ($role_get) {
                $transfer = new Transfer();
                $transfer->emp_id =$request->emp_id;
                $transfer->emp_name =$request->emp_name;
                $transfer->fromstore_id =$request->fromstore_id;
                $transfer->tostore_id =$request->tostore_id;
                $transfer->transfer_date =$request->transfer_date;
                $transfer->transfer_description =$request->transfer_description;
                $transfer->created_by =$user_id;

                 if ($role_get->role == 'Store Manager') {
                $transfer->request_to = 3;
            } elseif ($role_get->role == 'Manager') {
                $transfer->request_to = 1;
            }
            elseif ($role_get->role == 'Managing Director') {
                $transfer->request_to = 3;
            }
            else {
                $transfer->request_to = 12;
            }

                $transfer->save();
            } else {

                return response()->json(['error' => 'User role not found'], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transfer Request Sent successfully'
            ]);
    }

    public function storelist()
    {
        $store = DB::table('stores')->select('id','store_name')->where('status',1)->get();

        return response()->json([
            'data' => $store,
        ]);
    }



public function notification_list(Request $request)
{
    
    $user = $request->input('user_id');
    
    
    if (!$user) {
        
        return response()->json(['message' => 'Unauthorized'], 401);
    }


    $notifications = DB::table('notifications')
        ->select('id', 'user_id', 'noty_type', 'type_id', 'created_at')
        ->where('user_id', $user)
        ->get();

    if ($notifications->isEmpty()) {
        return response()->json(['message' => 'No notifications found', 'data' => []]);
    }

    $data = [];

    foreach ($notifications as $notification) {

        $details = null;

        switch ($notification->noty_type) {
            case 'task':
                $details = DB::table('tasks')
                    ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id') 
                    ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
                    ->leftJoin('users as assigned_to_user', 'tasks.assign_to', '=', 'assigned_to_user.id')
                    ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
                    ->leftJoin('roles as assigned_to_role', 'assigned_to_user.role_id', '=', 'assigned_to_role.id')
                    ->leftJoin('roles as assigned_by_role', 'assigned_by_user.role_id', '=', 'assigned_by_role.id')
                    ->where('tasks.id', $notification->type_id)
                    ->select(
                        'tasks.id',
                        'tasks.task_title',
                        'categories.category',
                        'sub_categories.subcategory',
                        'assigned_to_user.name as assigned_to_name',
                        'assigned_to_role.role as assigned_to_role',
                        'assigned_by_user.name as assigned_by_name',
                        'assigned_by_role.role as assigned_by_role'
                    )
                    ->orderBy('tasks.id','desc')
                    ->first();
                break;

            case 'leave':
                $details = DB::table('leaves')
                    ->leftJoin('users as request_to_user', 'leaves.request_to', '=', 'request_to_user.id')
                    ->leftJoin('users as esculate_to_user', 'leaves.esculate_to', '=', 'esculate_to_user.id')
                    ->leftJoin('roles as request_to_role', 'request_to_user.role_id', '=', 'request_to_role.id')
                    ->leftJoin('roles as esculate_to_role', 'esculate_to_user.role_id', '=', 'esculate_to_role.id')
                    ->where('leaves.id', $notification->type_id)
                    ->where('leaves.user_id', $user)
                    ->select(
                        'leaves.id as leave_id',
                        'leaves.request_type',
                        'leaves.start_date',
                        'leaves.end_date',
                        'leaves.request_to',
                        'request_to_user.name as request_to_name',
                        'request_to_role.role as request_to_role',
                        'leaves.esculate_to',
                        'esculate_to_user.name as esculate_to_name',
                        'esculate_to_role.role as esculate_to_role',
                        'leaves.user_id'
                    )
                    ->orderBy('leaves.id as leave_id','desc')
                    ->first();
                break;


            case 'transfer':
                $details = DB::table('transfers')
                    ->where('id', $notification->type_id)
                    ->where('emp_id', $user)
                    ->select('id as tra_id', 'transfer_description')
                    ->orderBy('transfers.id as tra_id','desc')
                    ->first();
                break;

            case 'resignation':
                $details = DB::table('resignations')
                    ->where('id', $notification->type_id)
                    ->where('emp_id', $user)
                    ->select('id as res_id', 'res_reason')
                    ->orderBy('resignations.id as res_id','desc')
                    ->first();
                break;

            default:
                Log::warning("Unknown notification type: " . $notification->noty_type);
                break;
        }

        // Convert created_at to IST
        $created_at_ist = $notification->created_at 
            ? Carbon::parse($notification->created_at)->toDateTimeString() 
            : null;

        // Add to response data
        $data[] = [
            'id' => $notification->id,
            'type_id' => $notification->type_id,
            'user_id' => $notification->user_id,
            'noty_type' => $notification->noty_type,
            'created_at' => $created_at_ist,
            'details' => $details ?? (object)[], // Ensure empty object instead of null
        ];
    }

    return response()->json(['data' => $data]);
}


public function tasktimeline(Request $request)
{
    $taskId = $request->input('id');

    if (!$taskId) {
        return response()->json([
            'message' => 'Task ID is required',
        ], 400);
    }

    $taskCheck = DB::table('tasks')
        ->where('id', $taskId)
        ->select('id', 'f_id')
        ->first();

    if (!$taskCheck) {
        return response()->json([
            'message' => 'Task not found',
        ], 404);
    }

    $queryTaskId = is_null($taskCheck->f_id) ? $taskId : $taskCheck->f_id;

    $tasks = DB::table('tasks')
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
        ->get();
        

    return response()->json([
        'data' => $tasks,
    ]);
}

    

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
