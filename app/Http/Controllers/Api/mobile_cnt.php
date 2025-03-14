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
    use common;


    public function  create_task_show(){

        $user = auth()->user()->role_id;

        $arr = hasAccess($user,'mob_task');

        return response()->json(["data"=>$arr],200);
    }

    public function assign_to()
    {

    $user = auth()->user();
    $r_id = $user->role_id;

    $cluster_check = DB::table('m_cluster as mc')
    ->leftJoin('users','users.id','=','mc.cl_name')
    ->where('mc.cl_name','=',$user->id)
    ->where('users.role_id',12)
    ->count();




    $arr = $this->role_arr();



  $list =  DB::table('users')
    ->leftJoin('roles','roles.id','=','users.role_id')
    ->select('users.name','roles.role','roles.role_dept','users.id','users.store_id');


    if(($r_id >= 12 && $r_id <= 19)) {

        if($cluster_check==0){
            $list->where('users.store_id', $user->store_id)
            ->where('users.id', '!=', $user->id);
        }else{

            $list->leftJoin('stores', 'stores.id', '=', 'users.store_id')
            ->where(function($query) use ($user) {
                // Include all users with role_id = 12
                $query->where('users.role_id', 12)
                      // Include all users from the current user's store
                      ->orWhere('users.store_id', $user->store_id);
                    })
            ->where('users.id', '!=', $user->id)
            ->orderBy('users.role_id');


        }


    }

    else{
        $list->leftJoin('stores', 'stores.id', '=', 'users.store_id')
        ->select('users.name', 'roles.role', 'roles.role_dept', 'users.id', 'users.store_id', 'stores.store_name', 'stores.store_code') // Adjust store fields as needed
        ->whereIn('users.role_id', $arr)
        ->where('users.id', '!=', $user->id);
        $list->orderBy('users.role_id');

    }

         $list = $list->get();


         return response()->json(["data"=>$list],200);

    } // function end

    public function attd_row()
    {

        $user_check = Auth::user()->id;

         $attd = DB::table('attendance')->where('user_id',$user_check)->whereDate('c_on', date('Y-m-d'))->count();

        if($attd==0){
            $val = 'attd_in';
        }else{
             $attd_ch = DB::table('attendance')->where('user_id',$user_check)->whereDate('c_on', date('Y-m-d'))->orderBy('id', 'desc')->first();
             if(is_null($attd_ch->out_time)){
                  $val = 'attd_out';
             }else{
                  $val = 'attd_mark';
             }

        }

        return response()->json([
            'status'=>'Success',
            'data' => [$attd_ch->in_time ?? null,$attd_ch->out_time ?? null,$val],
        ]);
    }

    public function attd_in(Request $req)
    {

        //  $user_check = $req->id;
          $user_check = Auth::user()->id;


        //   11.6754571,78.1320422

        $input = explode(',',$req->loc);

         // Get latitude and longitude from the request
        $latitude =  $input[0];
        $longitude = $input[1];

        // Google API Key
        $googleApiKey = env('GOOGLE_MAPS_API_KEY');

        // Make the request to the Google Geocoding API
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "{$latitude},{$longitude}",
            'key' => $googleApiKey
        ]);

        // // Decode the response
         $location = $response->json();


        // Check if the response was successful
    if ($location['status'] === 'OK') {
            $district = null;

            $result = $location['results'][0];
            $formattedAddress = $result['formatted_address'];

        foreach ($location['results'][0]['address_components'] as $component) {
                // Look for the district (usually administrative_area_level_2) or locality (city)
            if (in_array('administrative_area_level_2', $component['types'])) {
                $district = $component['long_name'];  // District
                break;
            }

            // If no district is found, you can try to use locality as a fallback
            if (in_array('locality', $component['types'])) {
                $district = $component['long_name'];  // Locality (City or Town)
                break;
            }
        } // end foreach
    }

        $inserted  = DB::table('attendance')->insertGetId([
                    'user_id' => $user_check,
                    'attend_status' => 'Present',
                    'in_location' => $district ?? 0,
                    'in_add' => $formattedAddress ?? 0,
                    'in_time' => now()->format('H:i:s'),
                    'c_on' => now()->format('Y-m-d'),
                    'status' => 'Active'
                ]);


    if(!is_null(Auth::user()->store_id)){

        $st_time = DB::table('stores')->where('id',Auth::user()->store_id)->select('stores.store_start_time','stores.store_end_time')->first();

        // dd($st_time);

     }


        $c_time = Carbon::now(); // Get the current time using Carbon

        if(!is_null(Auth::user()->store_id)){

        // Assuming store_start_time is stored in $st_time->store_start_time as a Carbon instance
        $start_time = Carbon::parse($st_time->store_start_time); // Convert store start time to a Carbon instance

        }else{
            $start_time = Carbon::parse('10:00:00'); // Co
        }

        // Calculate the 5-minute range (+5 and -5 minutes)
        $start_time_plus_5 = $start_time->copy()->addMinutes(5);
        $start_time_minus_5 = $start_time->copy()->subMinutes(5);

        if (!($c_time >= $start_time_minus_5 && $c_time <= $start_time_plus_5)) {


                  // Calculate the difference in hours, minutes, and seconds
                    $diff = $start_time->diff($c_time);

                    // Format the difference in hours, minutes, and seconds
                    $late = $diff->format('%H:%I');  // Example output: 02:30:00

                    // Output the result
                    // dd($formattedDiff);


                    DB::table('attd_ot')->insert([
                        'attd_id' => $inserted,
                        'cat' => 'late',
                        'time' => $late,
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
         }



        if($inserted){
             return response()->json(['status'=>'Success', ],200);
        }else{
            return response()->json(['status'=>'Failure'],500);
        }
    }

    public function attd_out(Request $req)
    {

        $user_check = Auth::user()->id;

        $attd = DB::table('attendance')->where('user_id',$user_check)->whereDate('c_on', date('Y-m-d'))->orderBy('id', 'desc')->first();



        $input = explode(',',$req->loc);

         // Get latitude and longitude from the request
        $latitude =  $input[0];
        $longitude = $input[1];

        // Google API Key
        $googleApiKey = env('GOOGLE_MAPS_API_KEY');

        // Make the request to the Google Geocoding API
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "{$latitude},{$longitude}",
            'key' => $googleApiKey
        ]);

        // // Decode the response
         $location = $response->json();


        // Check if the response was successful
    if ($location['status'] === 'OK') {
            $district = null;

            $result = $location['results'][0];
            $formattedAddress = $result['formatted_address'];

        foreach ($location['results'][0]['address_components'] as $component) {
            // Look for the district (usually administrative_area_level_2) or locality (city)
            if (in_array('administrative_area_level_2', $component['types'])) {
                $district = $component['long_name'];  // District
                break;
            }

            // If no district is found, you can try to use locality as a fallback
            if (in_array('locality', $component['types'])) {
                $district = $component['long_name'];  // Locality (City or Town)
                break;
            }
        }
    }



       $check_out =  DB::table('attendance')
            ->where('id', $attd->id)
            ->update([
            'out_time' => now()->format('H:i:s'),
            'out_location' => $district,
            'out_add' => $formattedAddress,
            'u_by'=>now()->format('Y-m-d')
             ]);


     if(!is_null(Auth::user()->store_id)){

        $st_time = DB::table('stores')->where('id',Auth::user()->store_id)->select('stores.store_start_time','stores.store_end_time')->first();

        // dd($st_time);

      }

             $c_time = now()->format('H:i:s');

             if(!is_null(Auth::user()->store_id)){
                $end_time = $st_time->store_end_time;
             }else{
                $end_time = '18:00:00';
             }

                if ($c_time > $end_time) {
                    // Define the two times
                    $time1 = Carbon::createFromFormat('H:i:s', $end_time);
                    $time2 = Carbon::createFromFormat('H:i:s', $c_time);

                    // Calculate the difference
                    $diff = $time1->diff($time2);

                    $ot = $diff->format('%H:%I');

                    DB::table('attd_ot')->insert([
                        'attd_id' => $attd->id,
                        'cat' => 'ot',
                        'time' => $ot,
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }


        if($check_out){
              return response()->json([
                'status'=>'Success'

            ],200);
        }else{
            return response()->json([
                'status'=>'Failure'
                 ],500);
        }

    }


    public function leavestore(Request $request)
    {
          $user_id = auth()->user();

          $role_get = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user_id->id)
            ->first();

        if ($role_get) {
            $leave = new Leave();
            if($request->request_type!='Permission'){
                 $leave->start_date = $request->start_date;
                 $leave->end_date = $request->end_date;
            }else{
                 $leave->start_date = $request->start_date;
                 $leave->end_date = $request->start_date;
            }

            $leave->request_type = $request->request_type;
            $leave->reason = $request->reason;
            $leave->start_time = $request->start_time;
            $leave->end_time = $request->end_time;
            $leave->user_id = $user_id->id;
            $leave->created_by = $user_id->id;

            if($user_id->role_id >= 13 && $user_id->role_id <= 19){

            $store_man = DB::table('users')->where('store_id',$user_id->store_id)->where('role_id',12)->first();
                    $leave->request_to = $store_man->id ?? 2;
                    $req_to = $store_man->id ?? 2;
                    $req_token  = DB::table('users')->where('id',$store_man->id ?? 2)->first();
            }

            else if(!hasAccess($user_id->role_id,'leave')){

               $dept = DB::table('roles')->where('id',$user_id->role_id)->select('role_dept')->first();

               switch($dept->role_dept) {
                    case 'HR':
                    $arr = 3;
                    break;
                   case 'Finance':
                       $arr = 7;
                       break;
                   case 'Maintenance':
                       $arr = 30;
                       break;
                   case 'Warehouse':
                       $arr = 37;
                       break;
                   case 'Purchase':
                       $arr = 41;
                       break;

               }

               $arr1 = DB::table('users')->where('role_id',$arr)->select('id')->first();

               // dd($dept);

               $leave->request_to = $arr1->id;
               $req_to = $arr1->id;
               $req_token  = DB::table('users')->where('id',$arr1->id)->first();

               }



            else{

                $leave->request_to = $request->request_to;
                $req_to = $request->request_to;
                $req_token  = DB::table('users')->where('id',$request->request_to)->first();
            }

            $leave->save();



            // if (!is_null($req_token->device_token)) {
            //         $taskTitle = $request->request_type."Request";
            //         $taskBody = $user_id->name. "Requested for " . $request->request_type;

            //         $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

            //         Notification::create([
            //             'user_id' => $req_to ?? 0,
            //             'noty_type' => 'leave',
            //             'type_id' => $leave->id
            //         ]);
            // } // notification end



        } else {

            return response()->json(['error' => 'User role not found'], 404);
        }



        return response()->json([
            'success' => true,
            'message' => 'Leave Request Sent successfully',
            'token'=>$req_token->device_token
        ]);
    }



    public function res_store(Request $request)
    {
        $user_id = auth()->user();

          $role_get = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user_id->id)
            ->first();

        if ($role_get) {

            $resgination = new Resignation();
            $resgination->emp_id =$request->emp_id;
            $resgination->emp_name =$request->emp_name;
            // $resgination->store_id =$request->store_id;
            $resgination->loc =$request->loc;
            $resgination->res_date =$request->res_date;
            $resgination->res_reason =$request->res_reason;
            $resgination->created_by=$user_id->id;

            $role = $role_get->role;
            $role_dept = $role_get->role_dept;

            if($user_id->role_id >= 13 && $user_id->role_id <= 19){

                $store_man = DB::table('users')->where('store_id',$user_id->store_id)->where('role_id',12)->first();
                        $resgination->request_to = $store_man->id ?? 2;
                         $req_to = $store_man->id ?? 2;
                        $req_token  = DB::table('users')->where('id',$store_man->id ?? 2)->first();
            }
            else if(!hasAccess($user_id->role_id,'leave')){

                $dept = DB::table('roles')->where('id',$user_id->role_id)->select('role_dept')->first();

                switch($dept->role_dept) {
                    case 'HR':
                        $arr = 3;
                        break;
                    case 'Finance':
                        $arr = 7;
                        break;
                    case 'Maintenance':
                        $arr = 30;
                        break;
                    case 'Warehouse':
                        $arr = 37;
                        break;
                    case 'Purchase':
                        $arr = 41;
                        break;

                }

                $arr1 = DB::table('users')->where('role_id',$arr)->select('id')->first();

                $resgination->request_to = $arr1->id;
                $req_to = $arr1->id;
                $req_token  = DB::table('users')->where('id',$arr1->id)->first();

            } else{
                    $resgination->request_to = $request->request_to;
                     $req_to = $request->request_to;
                    $req_token  = DB::table('users')->where('id',$request->request_to)->first();
                }


            $resgination->save();


            // dd($req_token->device_token);


            if ($req_token->device_token) {
                    $taskTitle = "Resignation Request";
                    $taskBody = $user_id->name. "Requested for Resignation";

                    $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

                    Notification::create([
                        'user_id' => $req_to,
                        'noty_type' => 'resignation',
                        'type_id' => $resgination->id
                    ]);
            } // notification end

        }

        return response()->json([
            'success' => true,
            'message' => 'Resgination Request Sent successfully',
        ]);


    }


    public function resign_show()
    {
        $user_id = Auth::user()->id;

        $resgination = DB::table('resignations')
        ->where('resignations.created_by',$user_id)
        ->leftjoin('users','users.id','=','resignations.emp_id')
        // ->leftJoin('resignations as rs','rs.emp_id','=', 'users.id')
        ->select('resignations.*','users.emp_code')

        ->get();



        return response()->json([

            'data' => [
                'resgination' => $resgination
            ]

        ]);
    }


}
