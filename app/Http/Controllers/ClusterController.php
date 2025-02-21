<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\{User, Role, };
use Carbon\Carbon;
use App\Http\Controllers\DashBoardController;

class ClusterController extends Controller
{
    public function index()
    {

        $cluster = DB::table('m_cluster as mc')
            ->leftjoin('cluster_store as cs', 'cs.cluster_id', '=', 'mc.id')
            ->leftJoin('users', function ($join) {
                $join->on('users.id', '=', 'mc.cl_name'); // Join on store_id and store_ref_id
            })
            ->select('mc.id', 'mc.cl_name', 'mc.location', DB::raw('COUNT(cs.cluster_id) as cl_count'), 'users.contact_no', 'users.email', 'users.name')
            ->groupBy('mc.id')
            ->get();
        // return $cluster;
        return view('cluster.list', ['cluster' => $cluster]);
    }

    public function cluster_overview()
    {

        $user = Auth::user()->id;
        $cluster = DB::table('m_cluster as mc')
            ->leftJoin('users as user', 'user.id', '=', 'mc.cl_name') // Joining users table
            ->where('mc.cl_name', $user) // Filter by the cluster id
            ->select('user.name', 'user.contact_no', 'user.email', 'mc.alter', 'user.address', 'user.pincode', 'mc.location', 'user.profile_image', 'mc.id') // Select the required fields from users table
            ->first(); // Get the first matching result

        // return $cluster;


        $cluster_list = DB::table('cluster_store as cs')
            ->leftJoin('stores as store', 'store.id', '=', 'cs.store_id') // Joining users table
            ->where('cs.cluster_id', $cluster->id)
            ->get();

        foreach ($cluster_list as $cs) {

            $st_man = DB::table('users')->where('role_id', 12)->where('store_id', $cs->store_id)->select('users.name')->first();

            $cs->st_name = $st_man ? $st_man->name : null;

        }

        // //   return($cluster_list);
        return view('cluster.overview', ['cl_data' => $cluster_list]);
    }

    public function cluster_mydashboard()
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

        return view('generaldashboard.mydashboard', ['tasks_todo' => $tasks_todo, 'tasks_todo_count' => $tasks_todo_count, 'tasks_inprogress' => $tasks_inprogress, 'tasks_inprogress_count' => $tasks_inprogress_count, 'tasks_onhold' => $tasks_onhold, 'tasks_onhold_count' => $tasks_onhold_count, 'tasks_complete' => $tasks_complete, 'tasks_complete_count' => $tasks_complete_count, 'employees' => $employees, 'role' => $role]);


        // return view ('cluster.mydashboard');
    }

    public function cluster_strength()
    {

        $user = Auth::user()->id;
        $cluster = DB::table('m_cluster as mc')
            ->leftJoin('users as user', 'user.id', '=', 'mc.cl_name') // Joining users table
            ->where('mc.cl_name', $user) // Filter by the cluster id
            ->select('mc.id') // Select the required fields from users table
            ->first(); // Get the first matching result

        //  $cluster_list= DB::table('cluster_store as cs')
        // ->leftJoin('stores as store', 'store.id', '=', 'cs.store_id') // Joining users table
        // ->leftJoin('store_lists as sl', function($join) {
        //     $join->on('sl.store_ref_id', '=', 'cs.store_id');// Join on store_id and store_ref_id
        // })
        // ->where('cs.cluster_id',$cluster->id)
        // ->select('store.store_code','sl.role_id','sl.req_count','sl.emp_count') // Select the required fields from users table
        // ->get();
        $store_list = DB::table('cluster_store as cs')
            ->leftJoin('stores as store', 'store.id', '=', 'cs.store_id') // Joining users table
            ->where('cs.cluster_id', $cluster->id)
            ->select('store.store_code', 'store.id','store.store_name') // Select the required fields from users table
            ->get();

        $role = [12, 13, 14, 15, 16, 17, 18, 19]; // List of role IDs
        foreach ($store_list as $sl) {
            // Initialize an empty array to store the final roles for this store
            $store_roles = [
                'sl' => $sl->store_code, // Store the store code as 'sl'
                'st_name' => $sl->store_name, // Store the store code as 'sl'
                'roles' => [] // This will hold the roles array
            ];

            foreach ($role as $r) {
                // Fetch the role data for the current store and role
                $rol_req = DB::table('store_lists')
                    ->where('store_ref_id', $sl->id)
                    ->where('role_id', $r)
                    ->select('role_id', 'req_count', 'emp_count')
                    ->first(); // Use first() to get a single result

                // Check if the result is empty (i.e., no data for this role)
                if (!$rol_req) {
                    // If no data, create a default entry with 0 counts
                    $rol_req = (object) [
                        'role_id' => $r,
                        'req_count' => 0,
                        'emp_count' => 0
                    ];
                }

                // Add the role data to the roles array
                $store_roles['roles'][] = [
                    'role_id' => $rol_req->role_id,
                    'req_count' => $rol_req->req_count,
                    'emp_count' => $rol_req->emp_count
                ];
            }

            // Add the store's roles with their data to the final list
            $sl_list[] = $store_roles;
        }

        // return $sl_list;

        return view('cluster.strength', ['store_list' => $sl_list]);
    }

    public function create(Request $req)
    {
        $create = DB::table('m_cluster')->insertGetId([
            'cl_name' => $req->clustername,
            'alter' => $req->altcontact,
            'location' => $req->storeloc,
            'created_at' => now(),  // Don't manually include these!
            'updated_at' => now()   // Don't manually include these!

        ]);

        foreach ($req->store as $st_id) {

            $create_list = DB::table('cluster_store')->insert([
                'cluster_id' => $create,
                'store_id' => $st_id,
                'created_at' => now(),  // Don't manually include these!
                'updated_at' => now()   // Don't manually include these!
            ]);
        }

        if ($create && $create_list) {
            return redirect()->route('cluster.index')->with([
                'status' => 'success',
                'message' => 'Cluster Added successfully!'
            ]);
        } else {

            return redirect()->route('cluster.index')->with([
                'status' => 'Failure',
                'message' => 'Cluster Failed to Add!'
            ]);


        }

    }

    public function drop_show()
    {
        // $cluster = DB::table('users')
        // ->leftjoin('m_cluster','users.id','!=','m_cluster.cl_name')
        // ->where('role_id','11')->where('status','1')->select('id','name')->get();

        $cluster = DB::table('users')
            ->leftJoin('m_cluster', 'users.id', '=', 'm_cluster.cl_name')  // Correct LEFT JOIN condition
            ->where('users.role_id', 11)  // Filter users with role_id = 11
            ->where('users.status', 1)  // Filter users with status = 1
            ->whereNull('m_cluster.cl_name')  // Exclude users whose ID is in the cl_name column
            ->select('users.id', 'users.name')
            ->get();



        // return $store;

        return view('cluster.add', ['cluster' => $cluster]);
    }

    public function cluster_det(Request $req)
    {
        $data = DB::table('users')->where('id', $req->cluster_per)->select('contact_no', 'email', 'address', 'district', 'state', 'pincode')->first();

        // $store = DB::table('stores')->where('status','1')->select('*')->get();

        $store = DB::table('store_lists')
            ->leftJoin('stores', 'store_lists.store_ref_id', '=', 'stores.id') // LEFT JOIN with the stores table
            ->leftJoin('users', function ($join) {
                $join->on('users.store_id', '=', 'store_lists.store_ref_id') // Join on store_id and store_ref_id
                    ->where('users.role_id', 12); // Additional condition for users' role_id = 12
            })
            ->where('store_lists.role_id', 12) // Filter store_lists by role_id = 12
            ->select(
                'store_lists.id',
                'store_lists.store_ref_id',
                'stores.store_code',
                'stores.store_name',
                'users.name as user_name', // Select the user name from the users table
                'stores.store_geo',
                'stores.store_contact'
            )
            ->get(); // Execute the query and get the result

        $new_obg = [];

        foreach ($store as $st) {

            $count = DB::table('cluster_store')->where('store_id', $st->store_ref_id)->count();

            if ($count > 0) {
                continue;
            } else {
                $new_obg[] = $st;
            }
        }


        $arr = [
            'data' => $data,
            'store' => $new_obg,
        ];

        return response()->json($arr, 200);
        // return view('');
    }

    public function show(string $id)
    {
        $cluster = DB::table('m_cluster as mc')
            ->leftJoin('users as user', 'user.id', '=', 'mc.cl_name') // Joining users table
            ->where('mc.id', $id) // Filter by the cluster id
            ->select('user.name', 'user.contact_no', 'user.email', 'mc.alter', 'user.address', 'user.pincode', 'mc.location', 'user.profile_image') // Select the required fields from users table
            ->first(); // Get the first matching result


        $cluster_list = DB::table('cluster_store as cs')
            ->leftJoin('stores as store', 'store.id', '=', 'cs.store_id') // Joining users table
            ->where('cluster_id', $id)->get();

        foreach ($cluster_list as $cs) {

            $st_man = DB::table('users')->where('role_id', 12)->where('store_id', $cs->store_id)->select('users.name')->first();

            $cs->st_name = $st_man ? $st_man->name : null;

        }



        //  return $cluster_list;
        return view('cluster.profile', ['mc' => $cluster, 'clust_store' => $cluster_list]);
    }
    public function edit()
    {
        return view('cluster.edit');
    }


    public function update()
    {
        return view('');
    }
    public function delete()
    {
        return view('');
    }
}
