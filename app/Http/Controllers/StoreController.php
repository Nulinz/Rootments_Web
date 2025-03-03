<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if($user->role_id==11){

            $store = DB::table('m_cluster as mc')
            ->leftJoin('cluster_store as cs','cs.cluster_id','=','mc.id')
            ->leftJoin('stores as st','st.id','=','cs.store_id')
            ->select('st.id','st.store_code','st.store_name','st.store_mail','st.store_contact')
            ->where('cl_name',$user->id)
            ->get();

        }else{

            $query = DB::table('stores');

            if ($user->dept !== 'Admin' && $user->dept !== 'HR') {
                $query->where('id', $user->store_id);
            }

            $store = $query->get();
        }




        return view('store.list',['store'=>$store]);
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
                    ->whereNotIn('id', [1,2,3,4,5,6,7,8,9,10,11])
                    ->groupBy('role')
                    ->get();



        return view('store.add',['store_no'=>$store_no,'role_data'=>$role_data]);

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
        $store= DB::table('stores')->where('id',$id)->first();

        return view('store.profile', ['store' => $store]);
    }
    public function strlist($id)
    {
        $strenth =DB::table('store_lists')
        ->leftjoin('roles', 'store_lists.role_id', '=', 'roles.id')
        ->where('store_lists.store_ref_id', $id)
        ->select('store_lists.*',
            'roles.role')
        ->get();

        return view('store.strength',['strenth'=>$strenth]);
    }

    public function detailslist($id)
    {
        $employee=DB::table('users')
        ->leftjoin('stores', 'users.store_id', '=', 'stores.id')
        ->leftjoin('roles', 'users.role_id', '=', 'roles.id')
        ->where('users.store_id', $id)
        ->select('users.emp_code','users.name','users.login_time','users.logout_time','roles.role','users.id as userId')
        ->get();

        return view('store.details',['employee'=>$employee]);
    }

    public function empview($userId)
    {
        $users = DB::table('users')
        ->leftJoin('stores', 'users.store_id', '=', 'stores.id')
        ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        ->where('users.id',$userId)
        ->select('users.id','users.profile_image','users.name','users.emp_code','users.contact_no','users.email','stores.store_name','roles.role','roles.role_dept')
        ->first();

        return view('employee.profile', ['users' => $users]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $store= DB::table('stores')->where('id',$id)->first();

        $role_data= DB::table('roles')->get();

        $storedata=DB::table('store_lists')
        ->join('stores', 'store_lists.store_ref_id', '=', 'stores.id')
        ->join('roles', 'store_lists.role_id', '=', 'roles.id')
        ->where('store_lists.store_ref_id', $store->id)
        ->select(
            'store_lists.*',
            'roles.id as role_id',
            'roles.role'
        )->get();


        return view('store.edit',['store'=>$store,'role_data'=>$role_data,'storedata'=>$storedata]);

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


    /**
     * Remove the specified resource from storage.
     */

}
