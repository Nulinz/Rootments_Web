<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
