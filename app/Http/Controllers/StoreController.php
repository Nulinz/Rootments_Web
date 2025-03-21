<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role_id == 11) {

            $store = DB::table('m_cluster as mc')
                ->leftJoin('cluster_store as cs', 'cs.cluster_id', '=', 'mc.id')
                ->leftJoin('stores as st', 'st.id', '=', 'cs.store_id')
                ->select('st.id', 'st.store_code', 'st.store_name', 'st.store_mail', 'st.store_contact')
                ->where('cl_name', $user->id)
                ->get();

        } else {

            $query = DB::table('stores');

            if ($user->dept !== 'Admin' && $user->dept !== 'HR') {
                $query->where('id', $user->store_id);
            }

            $store = $query->get();
        }




        return view('store.list', ['store' => $store]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $max_id = DB::table('stores')->max('id');

        $store_no = 'STORE' . str_pad($max_id + 1, 2, '0', STR_PAD_LEFT);

        // $role_data= DB::table('roles')->groupBY('role')->get();

        $role_data = DB::table('roles')
            ->whereNotIn('id', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])
            ->groupBy('role')
            ->get();



        return view('store.add', ['store_no' => $store_no, 'role_data' => $role_data]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|unique:stores,store_name',
            'store_contact' => 'required|unique:stores,store_contact',
        ]);

        $request->validate([
            'store_code' => 'required',
            'store_name' => 'required',
            'store_contact' => 'required',
            'store_start_time' => 'required',
            'store_end_time' => 'required',
            'store_address' => 'required',
            'store_pincode' => 'required',
            'store_geo' => 'required',
        ]);

        $store = [];

        $store[] = DB::table('stores')->insertGetId([
            'store_code' => $request->store_code,
            'store_name' => $request->store_name,
            'store_contact' => $request->store_contact,
            'store_mail' => $request->store_mail,
            'store_alt_contact' => $request->store_alt_contact,
            'store_start_time' => $request->store_start_time,
            'store_end_time' => $request->store_end_time,
            'store_address' => $request->store_address,
            'store_pincode' => $request->store_pincode,
            'store_geo' => $request->store_geo,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        $items = [];
        foreach ($request->role_id as $key => $roleId) {

            foreach ($store as $store_ref_id) {
                $items[] = [
                    'store_ref_id' => $store_ref_id,
                    'role_id' => $roleId,
                    'req_count' => $request->req_count[$key],
                    'emp_count' => $request->emp_count[$key],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('store_lists')->insert($items);

        return redirect()->route('store.index')->with([
            'status' => 'success',
            'message' => 'Store Added successfully!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $store = DB::table('stores')->where('id', $id)->first();

        return view('store.profile', ['store' => $store]);
    }
    public function strlist($id)
    {
        $strenth = DB::table('store_lists')
            ->leftjoin('roles', 'store_lists.role_id', '=', 'roles.id')
            ->where('store_lists.store_ref_id', $id)
            ->select(
                'store_lists.*',
                'roles.role'
            )
            ->get();

        return view('store.strength', ['strenth' => $strenth]);
    }

    public function detailslist($id)
    {
        $employee = DB::table('users')
            ->leftjoin('stores', 'users.store_id', '=', 'stores.id')
            ->leftjoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.store_id', $id)
            ->select('users.emp_code', 'users.name', 'users.login_time', 'users.logout_time', 'roles.role', 'users.id as userId')
            ->get();

        return view('store.details', ['employee' => $employee]);
    }

    public function empview($userId)
    {
        $users = DB::table('users')
            ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id', $userId)
            ->select('users.id', 'users.profile_image', 'users.name', 'users.emp_code', 'users.contact_no', 'users.email', 'stores.store_name', 'roles.role', 'roles.role_dept', 'users.status as u_status')
            ->first();

        return view('employee.profile', ['users' => $users]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $store = DB::table('stores')->where('id', $id)->first();

        $role_data = DB::table('roles')->get();

        $storedata = DB::table('store_lists')
            ->join('stores', 'store_lists.store_ref_id', '=', 'stores.id')
            ->join('roles', 'store_lists.role_id', '=', 'roles.id')
            ->where('store_lists.store_ref_id', $store->id)
            ->select(
                'store_lists.*',
                'roles.id as role_id',
                'roles.role'
            )->get();


        return view('store.edit', ['store' => $store, 'role_data' => $role_data, 'storedata' => $storedata]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update store details
        DB::table('stores')
            ->where('id', $id)
            ->update([
                'store_code' => $request->store_code,
                'store_name' => $request->store_name,
                'store_contact' => $request->store_contact,
                'store_mail' => $request->store_mail,
                'store_alt_contact' => $request->store_alt_contact,
                'store_start_time' => $request->store_start_time,
                'store_end_time' => $request->store_end_time,
                'store_address' => $request->store_address,
                'store_pincode' => $request->store_pincode,
                'store_geo' => $request->store_geo,
                'updated_at' => now(),
            ]);

        // Get new role IDs from request
        $newRoleIds = $request->role_id ?? [];

        // Delete old roles that are no longer present in the request
        DB::table('store_lists')
            ->where('store_ref_id', $id)
            ->whereNotIn('role_id', $newRoleIds)
            ->delete();

        // Process the updated roles
        foreach ($newRoleIds as $key => $roleId) {
            $existingItem = DB::table('store_lists')
                ->where('store_ref_id', $id)
                ->where('role_id', $roleId)
                ->first();

            if ($existingItem) {
                // Update existing role entry
                DB::table('store_lists')
                    ->where('store_ref_id', $id)
                    ->where('role_id', $roleId)
                    ->update([
                        'req_count' => $request->req_count[$key],
                        'emp_count' => $request->emp_count[$key],
                        'updated_at' => now(),
                    ]);
            } else {
                // Insert new role entry
                DB::table('store_lists')->insert([
                    'store_ref_id' => $id,
                    'role_id' => $roleId,
                    'req_count' => $request->req_count[$key],
                    'emp_count' => $request->emp_count[$key],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('store.index')->with([
            'status' => 'success',
            'message' => 'Store Updated successfully!'
        ]);
    }

    public function store_check(Request $req){

        $st = DB::table('set_up')->where('st_code', $req->store)->first();

        // dd($st);

        return response()->json([
            'st_code' => $st->st_code ?? null,
            'data' => $st
        ], 200);

    }

    public function addworkupdate()
    {

        $currentMonth = Carbon::now()->month;

        $sums = DB::table('work_update')
        ->whereMonth('created_at', $currentMonth)
        ->where('store_id',auth()->user()->store_id)
        ->select(
            DB::raw('SUM(b_mtd) as b_mtd_sum'),
            DB::raw('SUM(q_mtd) as q_mtd_sum'),
            DB::raw('SUM(w_mtd) as w_mtd_sum'),
            DB::raw('SUM(los_mtd) as los_mtd_sum')
        )
        ->first(); // Use first() to get a single result

        $count = DB::table('work_update')->whereDate('created_at',date("y-m-d"))->where('store_id',auth()->user()->store_id)->count();

        //  dd($count);

        return view('store.add-workupdate',['data'=>$sums,'count'=>$count]);
    }

    public function workupdatelist()
    {
        return view('store.list-workupdate');
    }

    public function store_work(Request $req)
    {


       $ins =  DB::table('work_update')->insert([
            'store_id' => auth()->user()->store_id,
            'b_ftd' => $req->b_ftd,
            'b_mtd' => $req->b_mtd,
            'b_ly' => $req->b_lymtd,
            'b_ltl' => $req->b_ltl,
            'q_ftd' => $req->q_ftd,
            'q_mtd' => $req->q_mtd,
            'q_ly' => $req->q_lymtd,
            'q_ltl' => $req->q_ltl,
            'w_ftd' => $req->w_ftd,
            'w_mtd' => $req->w_mtd,
            'w_ly' => $req->w_lymtd,
            'w_ltl' => $req->w_ltl,
            'los_ftd' => $req->los_ftd,
            'los_mtd' => $req->los_mtd,
            'los_abs' => $req->los_abs,
            'abs_ftd' => $req->abs_ftd,
            'abs_tgt' => $req->abs_tgt,
            'abs_ach' => $req->abs_ach,
            'abs_per' => $req->abs_per,
            'con_per' => $req->conversion,
            'c_by' => auth()->user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if($ins){
            return view('store.workupdate',['status'=>'success','message'=>'Work updated SuccessFully']);
        }else{
            return view('store.workupdate',['status'=>'success','message'=>'Work Failed to Add']);
        }


    }

}
