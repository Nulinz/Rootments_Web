<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Repair;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class RepairController extends Controller
{
    public function index()
    {
        return view('repair.list');
    }

    public function create()
    {
        return view('repair.add');

    }

    public function store(Request $request)
    {

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
