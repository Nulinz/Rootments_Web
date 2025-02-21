<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\{User,Role};
use Carbon\Carbon;

class HrDashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $managerRoles = DB::table('roles')
            ->whereIn('role', ['Manager', 'Store Manager'])
            ->pluck('id')
            ->toArray();

        $overview = DB::table('users')
            ->leftJoin('attendance', function ($join) {
                $join->on('users.id', '=', 'attendance.user_id')
                     ->whereDate('attendance.c_on', Carbon::today());
            })
            ->select(
                'users.id as user_id',
                'users.name',
                'users.profile_image',
                'attendance.in_time',
                'attendance.user_id',
                'attendance.attend_status',
                'attendance.out_time',
                'attendance.status',
                'attendance.in_location'
            )
            ->whereIn('users.role_id', $managerRoles)
            ->where('users.id', '!=', $user->id)
            ->get();

            $roleData = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('roles.role', DB::raw('COUNT(users.id) as count'))
            ->groupBy('roles.role')
            ->orderByDesc('count')
            ->get();

            $roleNames = [];
            $userCounts = [];

            foreach ($roleData as $data) {
                $roleNames[] = $data->role;
                $userCounts[] = (int) $data->count;
            }

            $role_get = DB::table('roles')
            ->join('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user->id)
            ->first();

            $pendingLeaves = DB::table('leaves')
            ->leftJoin('users', 'leaves.user_id', '=', 'users.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select(
                DB::raw("'Leave' as request_type"),
                'leaves.id',
                'users.name',
                'users.profile_image',
                'users.emp_code',
                'users.store_id',
                DB::raw('NULL as store_name'),
                'leaves.request_to',
                'leaves.status',
                'leaves.start_date',
                'leaves.end_date',
                'leaves.reason'
            )
            ->where('leaves.esculate_to', $role_get->role_id)
            ->where('leaves.status', 'Pending');

        // Fetch Pending Transfers
        $pendingTransfers = DB::table('transfers')
            ->leftJoin('users', 'transfers.emp_id', '=', 'users.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select(
                DB::raw("'Transfer' as request_type"),
                'transfers.id',
                'users.name',
                'users.profile_image',
                'users.emp_code',
                'users.store_id',
                DB::raw('NULL as store_name'),
                'transfers.request_to',
                'transfers.status',
                DB::raw('NULL as start_date'),
                DB::raw('NULL as end_date'),
                DB::raw('NULL as reason')
            )
            ->where('transfers.esculate_to', $role_get->role_id)
            ->where('transfers.status', 'Pending');

        // Fetch Pending Resignations
        $pendingResignations = DB::table('resignations')
            ->leftJoin('users', 'resignations.emp_id', '=', 'users.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select(
                DB::raw("'Resignation' as request_type"),
                'resignations.id',
                'users.name',
                'users.profile_image',
                'users.emp_code',
                'users.store_id',
                DB::raw('NULL as store_name'),
                'resignations.request_to',
                'resignations.status',
                DB::raw('NULL as start_date'),
                DB::raw('NULL as end_date'),
                DB::raw('NULL as reason')
            )
            ->where('resignations.esculate_to', $role_get->role_id)
            ->where('resignations.status', 'Pending');

        // Fetch Pending Recruitments
        $pendingRecruitments = DB::table('recruitments')
            ->leftJoin('stores', 'recruitments.store_id', '=', 'stores.id') // Join stores to get store_name
            ->select(
                DB::raw("'Recruitment' as request_type"),
                'recruitments.id',
                DB::raw('NULL as name'), // No user linked
                DB::raw('NULL as profile_image'),
                DB::raw('NULL as emp_code'),
                'recruitments.store_id',
                'stores.store_name',
                'recruitments.request_to',
                'recruitments.status',
                DB::raw('NULL as start_date'),
                DB::raw('NULL as end_date'),
                DB::raw('NULL as reason')
            )
            ->where('recruitments.request_to', $role_get->role_id)
            ->where('recruitments.status', 'Pending');

        // Combine all queries with UNION ALL
        $pendingRequests = $pendingLeaves
            ->unionAll($pendingTransfers)
            ->unionAll($pendingResignations)
            ->unionAll($pendingRecruitments)
            ->get();


        return view('hr.overview',['overview'=>$overview,'roleNames'=>$roleNames,'userCounts'=>$userCounts,'pendingRequests'=>$pendingRequests]);
    }

   public function mydashboard()
   {

    $authId = Auth::user()->id;

    $user = Auth::user();
    $role = Role::find($user->role_id);

    $managerRoleIds = DB::table('roles')
        ->whereIn('role', ['Manager', 'Store Manager'])
        ->pluck('id')
        ->toArray();

    $employeesQuery = DB::table('users')
        ->select('id', 'name')
        ->whereNotNull('role_id')
        ->where('id', '!=', $user->id)
        ->whereIn('role_id', $managerRoleIds);

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
       return view('hr.mydashboard', ['tasks_todo' => $tasks_todo,'tasks_todo_count'=>$tasks_todo_count,'tasks_inprogress'=>$tasks_inprogress,'tasks_inprogress_count'=>$tasks_inprogress_count,'tasks_onhold'=>$tasks_onhold,'tasks_onhold_count'=>$tasks_onhold_count,'tasks_complete'=>$tasks_complete,'tasks_complete_count'=>$tasks_complete_count,'employees'=>$employees,'role'=>$role]);
   }

   public function kpidashboard()
   {
       return view('hr.kpidashboard');
   }

}
