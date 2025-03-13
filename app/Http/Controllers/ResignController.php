<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResignController extends Controller
{

    public function list()
    {
      $app =   DB::table('resignations as rs')->where('rs.status','Approved')
      ->leftJoin('users','users.id','=','rs.created_by')
      ->leftJoin('roles','roles.id','=','users.role_id')
      ->select('rs.*','users.emp_code','users.name','roles.*','rs.id as res_id')
      ->get();

        return view('resign.list',['list'=>$app]);

    }

    public function profile(Request $req)
    {
        $pro =   DB::table('resignations as rs')->where('rs.id',$req->id)
      ->leftJoin('users','users.id','=','rs.created_by')
      ->leftJoin('roles','roles.id','=','users.role_id')
      ->select('rs.*','users.emp_code','users.name','roles.*','rs.id as res_id')
      ->first();

    //   dd($pro);

        return view('resign.profile',['pro'=>$pro]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
