<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OperationController extends Controller
{
    public function index()
    {

        $user = auth()->user();

        $list_cluster = DB::table('m_cluster as mc')
        ->leftJoin('users as us','us.id','=','mc.cl_name')
        ->select('us.name','mc.id as mc_id')->get();


        //Attendance Overview

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
                ->whereIn('users.role_id', [10,11])
                ->where('users.id', '!=', $user->id)
                ->get();


        return view('operation.overview',['overview'=>$overview,'list'=>$list_cluster,'store'=>$store ?? null]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
