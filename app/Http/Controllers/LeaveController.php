<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Leave;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;

        $leave=DB::table('leaves')
        ->leftjoin('users','leaves.user_id','=','users.id')
        ->select('leaves.*','users.name','users.emp_code',)
        ->where('leaves.created_by',$user_id)
        ->get();

        return view('leave.list',['leave'=>$leave]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hr = DB::table('users')->where('role_id', [3,4,5])->select('users.id','users.name')->get();

        return view('leave.add',['hr_list'=>$hr]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user_id = auth()->user();

          $role_get = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user_id->id)
            ->first();


            $leave = new Leave();
            $leave->start_date = $request->start_date;
            $leave->end_date = $request->end_date;
            $leave->request_type = $request->request_type;
            $leave->reason = $request->reason;
            $leave->start_time = $request->start_time;
            $leave->end_time = $request->end_time;
            $leave->user_id = $user_id->id;
            $leave->created_by = $user_id->id;

            $role = $role_get->role;
            $role_dept = $role_get->role_dept;

            if($user_id->role_id >= 13 && $user_id->role_id <= 19){

            $store_man = DB::table('users')->where('store_id',$user_id->store_id)->where('role_id',12)->first();
                    $leave->request_to = $store_man->id;
            }else{
                $leave->request_to = $request->request_to;
            }

            // $manager_departments = ['Operation', 'Finance', 'IT', 'Sales/Marketing', 'Area', 'Cluster'];

            // if ($role === 'Store Manager' && $role_dept === 'Store') {
            //     $leave->request_to = 3;
            // } elseif ($role === 'Manager') {
            //     if ($role_dept === 'HR') {
            //         $leave->request_to = 1;
            //     } elseif (in_array($role_dept, $manager_departments)) {
            //         $leave->request_to = 3;
            //     } else {
            //         $leave->request_to = 12;
            //     }
            // } elseif ($role === 'Managing Director') {
            //     $leave->request_to = 3;
            // } else {
            //     $leave->request_to = 12;
            // }


             $leave->save();

             Notification::create([
                        'user_id' => $user_id,
                        'noty_type' => 'leave',
                        'type_id' => $leave->id

                    ]);


        return redirect()->route('leave.index')->with([
            'status' => 'success',
            'message' => 'Leave Request Added successfully!'
        ]);
    }


    public function updateEscalate(Request $request)
    {
        DB::table('leaves')
            ->where('id', $request->id)
            ->update(['esculate_to' => 3, 'updated_at' => now()]);

        return response()->json(['message' => 'Escalated successfully!']);
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
