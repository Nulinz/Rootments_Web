<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseService;
use Illuminate\Support\Str;
use App\Models\Task;
use App\Models\User;
use App\Models\Role;
use App\Models\Leave;
use App\Models\Resignation;
use App\Models\Transfer;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Controllers\trait\common;


class mobile_cnt extends Controller
{
    public function assign_to()
    {
        $user = User::with('role')->where('emp_code', '')->first();

        $token = $user->createToken('token')->plainTextToken;

//     $user = auth()->user();
//     $r_id = $user->role_id;

//     $cluster_check = DB::table('m_cluster as mc')
//     ->leftJoin('users','users.id','=','mc.cl_name')
//     ->where('mc.cl_name','=',$user->id)
//     ->where('users.role_id',12)
//     ->count();




//     $arr = $this->role_arr();



//   $list =  DB::table('users')
//     ->leftJoin('roles','roles.id','=','users.role_id')
//     ->select('users.name','roles.role','roles.role_dept','users.id','users.store_id');


//     if(($r_id >= 12 && $r_id <= 19)) {

//         if($cluster_check==0){
//             $list->where('users.store_id', $user->store_id)
//             ->where('users.id', '!=', $user->id);
//         }else{

//             $list->leftJoin('stores', 'stores.id', '=', 'users.store_id')
//             ->where(function($query) use ($user) {
//                 // Include all users with role_id = 12
//                 $query->where('users.role_id', 12)
//                       // Include all users from the current user's store
//                       ->orWhere('users.store_id', $user->store_id);
//                     })
//             ->where('users.id', '!=', $user->id)
//             ->orderBy('users.role_id');


//         }


//     }

//     else{
//         $list->leftJoin('stores', 'stores.id', '=', 'users.store_id')
//         ->select('users.name', 'roles.role', 'roles.role_dept', 'users.id', 'users.store_id', 'stores.store_name', 'stores.store_code') // Adjust store fields as needed
//         ->whereIn('users.role_id', $arr)
//         ->where('users.id', '!=', $user->id);
//         $list->orderBy('users.role_id');

//     }

//          $list = $list->get();


         return response()->json($token,200);

    } // function end
}
