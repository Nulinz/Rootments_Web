<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreSetupController extends Controller
{
    public function list()
    {
        return view('setup.list');
    }

    public function create()
    {
        return view('setup.add');
    }

    public function profile()
    {
        return view('setup.profile');
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
