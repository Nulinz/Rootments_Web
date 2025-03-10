<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecruitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        return view('recruit.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('recruit.create');
    }
    
    public function candidate_profile(Request $request)
    {
        return view('recruit.candidate_profile');
    }

    public function add_interview(Request $request)
    {
        return view('recruit.add_interview');
    }

    public function edit_interview(Request $request)
    {
        return view('recruit.edit_interview');
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
    public function profile(Request $request)
    {
        return view('recruit.profile');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        return view('recruit.edit');
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
