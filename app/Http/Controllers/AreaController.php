<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AreaController extends Controller
{

    public function list()
    {
        return view('area.list');
    }

    public function create()
    {
        return view('area.add');
    }

    public function area_overview(Request $request)
    {
        return view('area.overview');
    }

    public function area_kpi(Request $request)
    {
        return view('area.areakpidashboard');
    }

    public function show(Request $request)
    {
        return view('area.profile');
    }

    public function edit(string $id)
    {
        return view('area.edit');
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
