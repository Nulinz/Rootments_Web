<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view ('payroll.list');
    }

    public function drop_show(Request $req)
    {
        $sal_mon = $req->sal_mon;

        $sal = explode('-',$sal_mon);

        $year = $sal[0];
        $mon = $sal[1];

        $store = DB::table('stores') // Start by selecting all stores
        ->leftJoin('m_salary', function($join) use ($mon, $year) {
            $join->on('stores.id', '=', 'm_salary.store') // Join on the store id
                 ->where('m_salary.month', '=', $mon)  // Filter by the provided month
                 ->where('m_salary.year', '=', $year); // Filter by the provided year
        })
        ->whereNull('m_salary.store') // Exclude stores that have matching records in m_salary
        ->select('stores.id','stores.store_name','stores.store_code') // Select the columns from stores table
        ->get(); // Retrieve the result

    // The $store will now contain only those stores that don't have a matching entry in the m_salary table for the given month and year




       return $store;

         return view('payroll.list',['store'=>$store]);
    }
    /**
     * Show the form for creating a new resource.
     */


    public function store_per(Request $req)
    {
        $user_list = DB::table('users as us')->where('store_id',$req->store)
        ->select('us.name','us.emp_code','us.base_salary')
        ->get();

         return $user_list;

        // return view ('payroll.payroll_list',['u_list'=>$user_list]);
    }

    public function payroll_list()
    {
        return view ('payroll.payroll_list');
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
