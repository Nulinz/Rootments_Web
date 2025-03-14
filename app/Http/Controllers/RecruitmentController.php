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

        $rec = DB::table('recruitments as rc')
        ->where('rc.c_by',$user_id)
        ->leftJoin('roles', 'roles.id','=','rc.role')
        ->select(
            'rc.id',
            'rc.dept',
            'rc.role',
            'rc.loc',
            'rc.vacancy',
            'rc.request_to',
            'rc.exp',
            'rc.description',
            'rc.status',
            'roles.role',
            'rc.res_date'

        )
        ->get();

        // dd($rec);

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

            // dd($role_data);

            $dept = DB::table('roles')
            ->where('id', '!=', 1)
            ->select('role_dept')
            ->distinct()
            ->get();

         return view('recuritment.add',['role_data'=>$role_data,'dept'=>$dept]);

    }

    public function get_roles(Request $req)
    {


        $role = Role::where('role_dept',$req->dept)->select('id','role')->get();

        return response()->json($role,200);

    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $req)
     {
         $user = Auth::user();

        //  dd($req->department);

         try {
             $recruitment_id = DB::table('recruitments')->insertGetId([
                'dept'   => $req->input('department'),
                'role' => $req->input('role'),
                'loc'   => $req->input('location'),
                'res_date'   => $req->input('res_date'),
                'vacancy'   => $req->input('vacancy'),
                'request_to'   => 3,
                'exp'   => $req->input('experience'),
                'description'   => $req->input('recruitdescp'),
                'c_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Notification::create([
                'user_id'   => $user->id,
                'noty_type' => 'recruitments',
                'type_id'   => $recruitment_id, // Corrected this
            ]);


             return redirect()->route('recruitment.index')->with([
                 'status' => 'success',
                 'message' => 'Recruitment added successfully!',
             ]);

         } catch (\Exception $e) {

            // dd($e);

             return redirect()->route('recruitment.index1')->with([
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
