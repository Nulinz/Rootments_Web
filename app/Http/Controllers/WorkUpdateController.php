<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkUpdateController extends Controller
{
    public function abstractlist()
    {
        $list = DB::table('work_update as wp')
        ->join('stores as s', 'wp.store_id', '=', 's.id')  // Assuming 'store_id' is the column in wp to reference store.
        ->whereMonth('wp.created_at', date("m"))
        ->whereYear('wp.created_at', date("Y"))  // Optional, to get rows only from this year.
        ->select('wp.*','s.store_name','s.store_code')
        ->groupBy('s.id')  // Grouping by store
        ->orderBy('wp.created_at', 'DESC')  // Get the latest
        ->get();

        // dd($list);


        return view('workupdate.abstract-list',['list'=>$list]);
    }

    public function reportlist()
    {
        $store = DB::table('stores')->get();

        return view('workupdate.report-workupdate',['stores'=>$store]);
    }

    public function store(Request $request)
    {
        //
    }

    public function daily_work(Request $req)
    {
        $list = DB::table('work_update')->where('store_id',$req->store)->whereDate('created_at',$req->date)->get();

        $store = DB::table('stores')->get();

        // dd($list);

        return view('workupdate.report-workupdate',['list'=>$list,'stores'=>$store]);

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
