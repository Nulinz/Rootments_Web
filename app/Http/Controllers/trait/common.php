<?php

namespace App\Http\Controllers\trait;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

trait common
{
    // A sample method inside the trait
    public function attd_index($dept)
    {
        // \Log::info($message);
        $user = Auth::user();

        $emp = DB::table('roles')->where('role_dept',$dept)
        ->leftJoin('users as us','us.role_id','=','roles.id')
        ->leftJoin('attendance',function($query){
            $query->on('us.id','=','attendance.user_id')
            ->whereDate('attendance.c_on', Carbon::today());
        })
     ->where('us.id', '!=', $user->id)
        ->select(
        'us.id as user_id',
        'us.name',
        'us.profile_image',
        'attendance.in_time',
        'attendance.user_id',
        'attendance.attend_status',
        'attendance.out_time',
        'attendance.status',
        'attendance.in_location')
        ->get();

        return $emp
        ;
    }

    // Another sample method inside the trait
    public function getFormattedDate($date)
    {
        // return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }
}


?>
