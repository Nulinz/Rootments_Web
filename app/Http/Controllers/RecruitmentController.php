<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Recruitment;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,Role};
use App\Models\Notification;

class RecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;

        $rec = DB::table('recruitments')
        ->leftJoin('stores', 'stores.id', '=', 'recruitments.store_id')
        ->leftJoin('recruitment_lists', 'recruitment_lists.rect_id', '=', 'recruitments.id')
        ->leftJoin('roles', 'recruitment_lists.role_id', '=', 'roles.id')
        ->where('recruitments.created_by',$user_id)
        ->select(
            'recruitments.id',
            'recruitments.store_id',
            'recruitments.store_name',
            'recruitments.res_date',
            'stores.store_code',
            'recruitments.status',
            DB::raw('GROUP_CONCAT(roles.role) as roles'),
            DB::raw('GROUP_CONCAT(recruitment_lists.vat_count) as vat_counts')
        )
        ->groupBy('recruitments.id', 'recruitments.store_id','recruitments.res_date', 'recruitments.store_name', 'stores.store_code') // Add store_name here
        ->get();


        return view('recuritment.list',['rec'=>$rec]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        $role = Role::find($user->role_id);

        $role_data = Role::join('role_based', 'roles.id', '=', 'role_based.assign_role_id')
            ->where('role_based.role_id', $role->id)
            ->select('roles.role_dept', 'roles.id', 'roles.role')
            ->groupBy('roles.role_dept', 'roles.id', 'roles.role')
            ->get();

        return view('recuritment.add',['role_data'=>$role_data]);

    }

    /**
     * Store a newly created resource in storage.
     */


     public function store(Request $request)
     {
         $user = Auth::user();

         $role_get = DB::table('roles')
             ->join('users', 'users.role_id', '=', 'roles.id')
             ->select('roles.id', 'roles.role', 'roles.role_dept')
             ->where('users.id', $user->id)
             ->first();



         $role = $role_get->role;
         $role_dept = $role_get->role_dept;

         $manager_departments = ['Operation', 'Finance', 'IT', 'Sales/Marketing', 'Area', 'Cluster'];
         $request_to = 12;

         if ($role === 'Store Manager' && $role_dept === 'Store') {
             $request_to = 3;
         } elseif ($role === 'Manager') {
             $request_to = ($role_dept === 'HR') ? 1 : (in_array($role_dept, $manager_departments) ? 3 : 12);
         } elseif ($role === 'Managing Director') {
             $request_to = 3;
         }

         DB::beginTransaction();

         try {
             $recruitment_id = DB::table('recruitments')->insertGetId([
                'store_id'   => $request->input('store_id'),
                'store_name' => $request->input('store_name'),
                'res_date'   => $request->input('res_date'),
                'request_to' => $request_to,
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            Notification::create([
                'user_id'   => $user->id,
                'noty_type' => 'recruitments',
                'type_id'   => $recruitment_id, // Corrected this
            ]);


             $items = [];
             $role_ids = $request->input('role_id', []);
             $vat_counts = $request->input('vat_count', []);

             foreach ($role_ids as $key => $roleId) {
                 $items[] = [
                     'rect_id'    => $recruitment_id,
                     'role_id'    => $roleId,
                     'vat_count'  => $vat_counts[$key] ?? 0,
                     'created_at' => now(),
                     'updated_at' => now(),
                 ];
             }

             if (!empty($items)) {
                 DB::table('recruitment_lists')->insert($items);
             }

             DB::commit();
             
             
             return redirect()->route('recruitment.index')->with([
                 'status' => 'success',
                 'message' => 'Recruitment added successfully!',
             ]);

         } catch (\Exception $e) {
             DB::rollBack();

             return redirect()->route('recruitment.index')->with([
                 'status' => 'error',
                 'message' => 'Failed to add recruitment: ' . $e->getMessage(),
             ]);
         }
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
