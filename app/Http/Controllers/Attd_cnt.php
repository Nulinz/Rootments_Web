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
    public function daily_attd()
    {
        return view ('attendance.daily_list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function monthly_attd()
    {
        return view ('attendance.monthly_list');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function individual_attd()
    {
        return view ('attendance.individual_list');
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
