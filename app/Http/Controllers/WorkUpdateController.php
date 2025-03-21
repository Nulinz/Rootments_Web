<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkUpdateController extends Controller
{
    public function abstractlist()
    {
        return view('workupdate.abstract-list');
    }

    public function reportlist()
    {
        return view('workupdate.report-workupdate');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
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
