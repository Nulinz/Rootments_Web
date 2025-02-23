<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\{User,Role};
use Carbon\Carbon;

class DashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $store = DB::table('stores')->where('id', $user->store_id)->first();

        $storeMembers = DB::table('users')->where('store_id', $user->store_id)->pluck('id')->toArray();


        $role_get = DB::table('roles')
            ->join('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user->id)
            ->first();

       $storeManagerRole = DB::table('roles')
                        ->where('role', 'Store Manager')
                        ->value('id');

       $overview = DB::table('users')
                ->leftJoin('attendance', function ($join) {
                    $join->on('users.id', '=', 'attendance.user_id')
                         ->whereDate('attendance.c_on', Carbon::today());
                })
                ->select('users.id as user_id', 'users.name', 'users.profile_image', 'attendance.in_time', 'attendance.user_id', 'attendance.attend_status', 'attendance.out_time','attendance.status','attendance.in_location')
                ->where('users.store_id', $store->id)
                ->where('users.role_id', '!=', $storeManagerRole)
                ->get();


        if (!empty($storeMembers)) {
                $pendingLeaves = DB::table('leaves')
                    ->leftJoin('users', 'leaves.user_id', '=', 'users.id')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->select(
                        'leaves.*',
                        'users.name',
                        'users.profile_image',
                        'users.emp_code',
                        'users.store_id',
                        'leaves.request_to'
                    )
                    ->whereIn('users.id', $storeMembers)
                    ->where('leaves.request_to',  $user->id)
                    ->where('leaves.request_status', 'Pending')
                    ->get();
            } else {
                $pendingLeaves = collect();
            }

             if (!empty($storeMembers)) {
                $teampertask = DB::table('tasks')
                    ->join('users', 'tasks.assign_to', '=', 'users.id')
                    ->whereIn('tasks.assign_to', $storeMembers)
                    ->selectRaw("
                        users.name,
                        COUNT(*) AS total_tasks
                    ")
                    ->groupBy('users.name')
                    ->get();
            } else {
                $teampertask = collect();
            }

            $staffNames = $teampertask->pluck('name')->toArray();
            $taskCounts = $teampertask->pluck('total_tasks')->toArray();

            if (!empty($storeMembers)) {
                $tolatask = DB::table('tasks')
                    ->whereIn('tasks.assign_to', $storeMembers)
                    ->selectRaw("
                        SUM(CASE WHEN task_status = 'To Do' THEN 1 ELSE 0 END) AS todo,
                        SUM(CASE WHEN task_status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress,
                        SUM(CASE WHEN task_status = 'On Hold' THEN 1 ELSE 0 END) AS on_hold,
                        SUM(CASE WHEN task_status = 'Completed' THEN 1 ELSE 0 END) AS completed
                    ")
                    ->first();

                $tolatask = [
                    'todo' => $tolatask->todo ?? 0,
                    'in_progress' => $tolatask->in_progress ?? 0,
                    'on_hold' => $tolatask->on_hold ?? 0,
                    'completed' => $tolatask->completed ?? 0,
                ];
            } else {
                $tolatask = [
                    'todo' => 0,
                    'in_progress' => 0,
                    'on_hold' => 0,
                    'completed' => 0,
                ];
            }

           $categoryTask = DB::table('tasks')
                            ->join('categories', 'tasks.category_id', '=', 'categories.id')
                            ->whereIn('tasks.assign_to', $storeMembers)
                            ->where('tasks.task_status', 'Completed')
                            ->select(
                                'categories.category',
                                DB::raw('COUNT(*) as total_tasks')
                            )
                            ->groupBy('categories.category')
                            ->get();

                        $categoryNames = $categoryTask->pluck('category')->toArray();
                        $categorytaskCounts = $categoryTask->pluck('total_tasks')->toArray();

           $subcategoryTask = DB::table('tasks')
                            ->join('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
                            ->join('categories', 'tasks.category_id', '=', 'categories.id')
                            ->whereIn('tasks.assign_to', $storeMembers)
                            ->where('tasks.task_status', 'Completed')
                            ->select(
                                'categories.category',
                                'sub_categories.subcategory',
                                DB::raw('COUNT(*) as subtotal_tasks')
                            )
                            ->groupBy('categories.category', 'sub_categories.subcategory')
                            ->get();
                $subcategoryNames = $subcategoryTask->pluck('subcategory')->toArray();
                $subcategorytaskCounts = $subcategoryTask->pluck('subtotal_tasks')->toArray();

                // $attd_data = $this->attd_row();

                // dd($attd_data);


         return view('store.overview',['overview'=>$overview,'pendingLeaves'=>$pendingLeaves,'tolatask'=>$tolatask,'staffNames'=>$staffNames,'taskCounts'=>$taskCounts,'categoryNames'=>$categoryNames,'categorytaskCounts'=>$categorytaskCounts,'subcategoryNames'=>$subcategoryNames,'subcategorytaskCounts'=>$subcategorytaskCounts]);
    }

    public function attendanceApprove(Request $request)
    {
        $user_id = $request->input('user_id');

        $updated = DB::table('attendance')
                     ->where('user_id', $user_id)
                     ->update(['status' => 'approved']);

        if ($updated) {
            return response()->json(['success' => true, 'message' => 'Attendance approved!']);
        }

        return response()->json(['success' => false, 'message' => 'User not found or already approved!']);
    }

    public function generalstoreindex()
    {


        $user = auth()->user();

        $store = DB::table('stores')->where('id', $user->store_id)->first();

        if($store){
             $users = DB::table('users')
            ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.id as user_id', 'users.name', 'users.emp_code', 'roles.role', 'users.profile_image')
            ->where('users.store_id', $store->id)
            ->where('users.id', '!=', $user->id)
            ->get();
        }else{
            $users = DB::table('users')
            ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.id as user_id', 'users.name', 'users.emp_code', 'roles.role', 'users.profile_image')
            ->where('users.id', '!=', $user->id)
            ->get();
        }



        return view('store.storedashboard', ['users' => $users]);
    }

    public function useragainststask(Request $request)
    {



        $user_id = $request->input('user_id');

         $tasks_todo = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $user_id)
            ->where('tasks.task_status', 'To Do')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

            $tasks_todo_count = DB::table('tasks')
            ->where('assign_to', $user_id)
            ->where('task_status', 'To Do')
            ->count();

            $tasks_inprogress = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $user_id)
            ->where('tasks.task_status', 'In Progress')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

            $tasks_inprogress_count = DB::table('tasks')
            ->where('assign_to', $user_id)
            ->where('task_status', 'In Progress')
            ->count();

            $tasks_onhold = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $user_id)
            ->where('tasks.task_status', 'On Hold')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

            $tasks_onhold_count = DB::table('tasks')
            ->where('assign_to', $user_id)
            ->where('task_status', 'On Hold')
            ->count();

            $tasks_complete = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $user_id)
            ->where('tasks.task_status', 'Completed')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

            $tasks_complete_count = DB::table('tasks')
            ->where('assign_to', $user_id)
            ->where('task_status', 'Completed')
            ->count();

           return response()->json([
                'tasks_todo' => $tasks_todo,
                'tasks_todo_count' => $tasks_todo_count,
                'tasks_inprogress' => $tasks_inprogress,
                'tasks_inprogress_count' => $tasks_inprogress_count,
                'tasks_onhold' => $tasks_onhold,
                'tasks_onhold_count' => $tasks_onhold_count,
                'tasks_complete' => $tasks_complete,
                'tasks_complete_count' => $tasks_complete_count,
            ]);



    }

    public function mydashboardindex()
    {
       $authId = Auth::user()->id;

       $user = Auth::user();

        $role = Role::find($user->role_id);

        $store = DB::table('stores')->where('id', $user->store_id)->first();

        $employeesQuery = DB::table('users')
            ->select('id', 'name')
            ->whereNotNull('role_id');

        if (!in_array($user->dept, ['Admin', 'HR']) && $store) {
            $employeesQuery->where('store_id', $store->id);
        }

        $employeesQuery->where('id', '!=', $user->id);

        $employees = $employeesQuery->get();


       $tasks_todo = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $authId)
            ->where('tasks.task_status', 'To Do')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

            $tasks_todo_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'To Do')
            ->count();

            $tasks_inprogress = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $authId)
            ->where('tasks.task_status', 'In Progress')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

            $tasks_inprogress_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'In Progress')
            ->count();

            $tasks_onhold = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $authId)
            ->where('tasks.task_status', 'On Hold')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();

            $tasks_onhold_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'On Hold')
            ->count();

            $tasks_complete = DB::table('tasks')
            ->leftJoin('categories', 'tasks.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->leftJoin('roles as assigned_role', 'tasks.assign_to', '=', 'assigned_role.id')
            ->leftJoin('roles as assigned_by_role', 'tasks.assign_by', '=', 'assigned_by_role.id')
            ->leftJoin('users as assigned_by_user', 'tasks.assign_by', '=', 'assigned_by_user.id')
            ->where('tasks.assign_to', $authId)
            ->where('tasks.task_status', 'Completed')
            ->select(
                'tasks.*',
                'categories.category',
                'sub_categories.subcategory',
                'assigned_role.role as assigned_role',
                'assigned_by_role.role as task_assigned',
                'assigned_by_user.name as assigned_by'
            )
            ->orderBy('tasks.id', 'DESC')
            ->get();


            $tasks_complete_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'Completed')
            ->count();

        return view('generaldashboard.mydashboard', ['tasks_todo' => $tasks_todo,'tasks_todo_count'=>$tasks_todo_count,'tasks_inprogress'=>$tasks_inprogress,'tasks_inprogress_count'=>$tasks_inprogress_count,'tasks_onhold'=>$tasks_onhold,'tasks_onhold_count'=>$tasks_onhold_count,'tasks_complete'=>$tasks_complete,'tasks_complete_count'=>$tasks_complete_count,'employees'=>$employees,'role'=>$role]);
    }



    public function updateTaskStatus(Request $request)
    {
        $taskId = $request->id;
        $newStatus = $request->status;

        $authId = Auth::user()->id;

        $task = Task::find($taskId);
        if ($task) {
            $task->task_status = $newStatus;
            $task->save();

              $tasks_todo_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'To Do')
            ->count();

        $tasks_inprogress_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'In Progress')
            ->count();

        $tasks_onhold_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'On Hold')
            ->count();

        $tasks_complete_count = DB::table('tasks')
            ->where('assign_to', $authId)
            ->where('task_status', 'Completed')
            ->count();

           return response()->json([
            'success' => true,
            'message' => 'Task status updated',
            'taskCounts' => [
                'todo' => $tasks_todo_count,
                'inprogress' => $tasks_inprogress_count,
                'onhold' => $tasks_onhold_count,
                'complete' => $tasks_complete_count
            ]
        ]);
    }

    return response()->json(['success' => false, 'message' => 'Task not found']);
    }


    public function attd_row()
    {

        $user_check = Auth::user()->id;

        $attd = DB::table('attendance')->where('user_id',$user_check)->whereDate('c_on', date('Y-m-d'))->count();

        if($attd==0){
            $val = 'attd_in';
        }else{
             $attd_ch = DB::table('attendance')->where('user_id',$user_check)->whereDate('c_on', date('Y-m-d'))->orderBy('id', 'desc')->first();
             if(is_null($attd_ch->out_location)){
                  $val = 'attd_out';
             }else{
                  $val = 'attd_mark';
             }

        }

        // dd($val);

        return with([
            'attd_data' => [$attd_ch->in_time ?? null,$attd_ch->out_time ?? null,$val],
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
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
