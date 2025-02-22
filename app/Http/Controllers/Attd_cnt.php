<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Attd_cnt extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function daily_attd(Request $req)
{
    // Fetch all stores for the dropdown
    $stores = DB::table('stores')->get();

    // Initialize the attendance list as an empty collection
    $list = collect();

    // If the form has been submitted (POST request), filter the attendance data
    if ($req->isMethod('post') && $req->has('stores') && $req->has('date')) {
        $list = DB::table('attendance')
            ->leftJoin('users', 'users.id', '=', 'attendance.user_id')
            ->leftJoin('stores', 'users.store_id', '=', 'stores.id') // Corrected join condition
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->whereDate('attendance.c_on', '=', $req->date)
            ->where('users.store_id', $req->stores) // Additional condition for store_id
            ->select(
                'users.name', 'users.emp_code', 'roles.role', 'attendance.in_time',
                'attendance.in_location', 'attendance.out_time', 'attendance.status',
                'attendance.out_location', 'attendance.id as attd_id', 'users.id as u_id',
                'stores.id as s_id'
            )
            ->get();
    }

    // Return the view with the stores and the filtered (or empty) attendance list
    return view('attendance.daily_list', ['stores' => $stores, 'lists' => $list]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function monthly_attd(Request $req)
    {
         // Fetch all stores for the dropdown
    $stores = DB::table('stores')->get();

    // Initialize the attendance list as an empty collection
    $attendanceCounts = collect();

    // If the form has been submitted (POST request), filter the attendance data
    if ($req->isMethod('post') && $req->has('stores') && $req->has('month')) {
        $mon = $req->month;
        $sl_mon = explode('-',$mon);

        $month = $sl_mon[1];
        $year = $sl_mon[0];
        $attendanceCounts = DB::table('attendance')
            ->join('users', 'attendance.user_id', '=', 'users.id')  // Join users table with attendance based on user_id
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->select('attendance.user_id', 'users.name','users.emp_code', 'users.store_id', DB::raw('COUNT(*) as attd_count'),'roles.role')
            ->whereMonth('attendance.c_on', $month)  // Filter by the month
            ->whereYear('attendance.c_on', $year)    // Filter by the year
            ->where('users.store_id', $req->stores)     // Filter users by store_id (you can pass $store_id from the request)
            ->groupBy('attendance.user_id', 'users.name','users.store_id')  // Group by user_id, user name, and store_id
            ->get();
    }

    // return $req;
            // Return the view with the stores and the filtered (or empty) attendance list
             return view('attendance.monthly_list', ['stores' => $stores, 'lists' => $attendanceCounts]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function individual_attd()
    {

        $stores = DB::table('stores')->get();

        return view ('attendance.individual_list',['stores' => $stores]);
    }

    public function overtime_attd()
    {
        $att_ot = DB::table('attd_ot as over')
        ->leftJoin('attendance as at','at.id','=','over.attd_id')
        ->leftJoin('users','users.id','=','at.user_id')
        ->leftJoin('roles','roles.id','=','users.role_id')
        ->leftJoin('stores','stores.id','=','users.store_id')
        ->select('users.name','users.emp_code','roles.role','stores.store_name','over.cat','over.time','over.id','at.c_on')
        ->where('over.status','pending')
        ->orderByDesc('over.id')
        ->get();

        // return($att_ot);
         return view ('attendance.overtime_list',['ot_lists'=>$att_ot]);
    }


    public function get_store_per(Request $req)
    {

        $emp_list = DB::table('users')->where('store_id',$req->store_id)->select('id','name')->get();

        return response()->json($emp_list,200);
    }


    public function ot_approve(Request $req)
    {

        $prime_id = $req->input('attd_id');

        $updated = DB::table('attd_ot')
                     ->where('id', $prime_id)
                     ->update(['status' => 'approved']);

        if ($updated) {
            return response()->json(['success' => true, 'message' => 'OT or Late approved!']);
        }

        return response()->json(['success' => false, 'message' => 'User not found or already approved!']);

    }


    public function get_ind_attd(Request $req)
    {

        $mon = $req->month;
        $sl_mon = explode('-',$mon);

        $month = $sl_mon[1];
        $year = $sl_mon[0];

        $emp_list = DB::table('attendance')
        ->join('users', 'attendance.user_id', '=', 'users.id')
        ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
        ->where('attendance.user_id',$req->employee)
        ->whereMonth('attendance.c_on', $month)
        ->select('attendance.in_location','attendance.out_location','attendance.in_time','attendance.out_time',DB::raw('DATE_FORMAT(attendance.c_on, "%d-%m-%Y") as c_on'))->orderBy('attendance.id','DESC')->get();

        return response()->json($emp_list,200);
    }

    public function attd_row()
    {

        $user_check = Auth::user()->id;

        $attd = DB::table('attendence')->where('user_id',$user_check)->whereDate('c_on', date('Y-m-d'))->count();

        if($attd==0){
            $val = 'attd_in';
        }else{
             $attd_ch = DB::table('attendence')->where('user_id',$user_check)->whereDate('c_on', date('Y-m-d'))->orderBy('id', 'desc')->first();
             if(is_null($attd_ch->out_location)){
                  $val = 'attd_out';
             }else{
                  $val = 'attd_mark';
             }

        }

        return with([
            'attd_data' => [$attd_ch->in_time ?? null,$attd_ch->out_time ?? null,$val],
        ]);
    }

     public function attd_in(Request $req)
    {
         $user_check = Auth::user()->id;

         $inserted = DB::table('attendence')->insert([
        'user_id' => $user_check,
        'attend_status' => 'Present',
        'in_location' => $req->loc,
        'in_time' => now()->setTimezone('Asia/Kolkata')->format('H:i:s'),
        'c_on' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d')
       ]);

        return response()->json([
            'status'=>'Success',

        ]);
    }

     public function attd_out(Request $req)
    {

        $user_check = Auth::user()->id;

        $attd = DB::table('attendence')->where('user_id',$user_check)->whereDate('c_on', date('Y-m-d'))->orderBy('id', 'desc')->first();


        DB::table('attendence')
            ->where('id', $attd->id)
            ->update([
            'out_location'=>$req->loc,
            'out_time' => now()->setTimezone('Asia/Kolkata')->format('H:i:s'),
            'u_by' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d')
             ]);

        return response()->json([
            'status'=>'Success',

        ]);
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
