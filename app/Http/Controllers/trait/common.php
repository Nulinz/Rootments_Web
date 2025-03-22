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
        'attendance.c_on',
        'attendance.attend_status',
        'attendance.out_time',
        'attendance.status',
        'attendance.in_location')
        ->get();

        return $emp
        ;
    }

    // Another sample method inside the trait
    public function get_emp_dept($dept,$st=1)
    {
       // \Log::info($message);

       $user = Auth::user();

       if(($dept=='HR')||($dept=='Admin')){

        $emp = DB::table('users as us')->where('us.status','=',$st)
        ->whereNotNull('us.role_id')
        ->leftJoin('roles','roles.id','=','us.role_id')
             ->select(
             'us.id',
             'us.name',
             'us.emp_code',
             'us.email',
             'us.contact_no',
             'roles.role',
             'roles.role_dept')
             ->get();

       }else{

        $emp = DB::table('roles')->where('role_dept',$dept)
        ->leftJoin('users as us','us.role_id','=','roles.id')
        ->whereNotNull('us.role_id')
             ->select(
             'us.id',
             'us.name',
             'us.emp_code',
             'us.email',
             'us.contact_no',
             'roles.role',
             'roles.role_dept')
             ->get();

            //  dd($emp->toSql());

       }



            return $emp;
    }


    // Another sample method inside the trait
    public function role_arr()
    {
       // \Log::info($message);
       $user = Auth::user();
       $r_id = $user->role_id;

       $cluster_check = DB::table('m_cluster as mc')
       ->leftJoin('users','users.id','=','mc.cl_name')
       ->where('mc.cl_name','=',$user->id)
       ->where('users.role_id',12)
       ->count();

       switch($r_id) {
        case 1:
            $arr = [1];
            break;
        case 2:
            $arr = [2];
            break;
        case 3:
        case 4:
        case 5:
            $arr = [3, 4, 5, 26, 27, 6, 7, 8, 9, 10, 11, 12, 13,30,37,41,43];
            break;
        case 7:
            $arr = [25,44];
            break;
        case 10:
            $arr = [11, 12];
            break;
        case 11:
            $arr = [12];
            break;
        case 12:
            if($cluster_check==0){
                $arr = [13, 14, 15, 16, 17, 18, 19];
            }else{
                $arr = [12 ,13, 14, 15, 16, 17, 18, 19];
            }

            break;
        case 13:
        case 14:
        case 15:
        case 16:
        case 17:
        case 18:
        case 19:
            $arr = range(12, 19);  // Array from 12 to 19
            $arr = array_diff($arr, [$r_id]); // Exclude the current role ID
            break;
        case 25:
            $arr = [7,44,25];
            break;
        case 30:
            $arr = [31,35,36];
            break;
        case 31:
        case 35:
        case 36:
            $arr = [30, 31, 35, 36];
            // $arr = array_diff($arr, [$r_id]); // Exclude $r_id
            break;
        case 37:
            $arr = [38,39,40];
            break;
        case 38:
        case 39:
        case 40:
            $arr = [37, 38, 39, 40];
            // $arr = array_diff($arr, [$r_id]); // Exclude $r_id
            break;
        case 41:
            $arr = [42];
            break;
        case 42:
            $arr = [42,41];
            break;
        case 43:
        case 26:
        case 27:
            $arr = [3,4,5,26,27,43];
            // $arr = array_diff($arr, [$r_id]); // Exclude $r_id
            break;
        case 44:
            $arr = [7,25];
            break;
    }


        return $arr;
    }
}


?>
