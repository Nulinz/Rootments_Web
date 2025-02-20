<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Repair;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class RepairController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;

        $repair=DB::table('repairs')
                ->leftjoin('stores','stores.id','=','repairs.store_code')
                ->select('repairs.*','stores.store_code')
                ->where('repairs.created_by',$user_id)
                ->get();

        return view('repair.list',['repair'=>$repair]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('repair.add');

    }
    public function getstorename(Request $request)
    {
         $user_id = auth()->user()->id;

        $storename = DB::table('stores')
            ->leftJoin('users', 'users.store_id', '=', 'stores.id')
            ->select('stores.id', 'stores.store_code', 'stores.store_name')
            ->where('users.id', $user_id)
            ->first();


        return response()->json($storename);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $user_id = auth()->user()->id;

          $role_get = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user_id)
            ->first();

        if ($role_get) {
            $repair = new Repair();
            $repair->store_code = $request->store_code;
            $repair->store_name = $request->store_name;
            $repair->repair_date = $request->repair_date;
            $repair->repair_description = $request->repair_description;

            if ($request->hasFile('repair_file')) {
                    $file = $request->file('repair_file');
                    $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
                    $path = 'assets/images/Repair/';
                    $file->move($path, $name);

                    $repair->repair_file = $path . $name;
                }

            $repair->created_by = $user_id;
            if ($role_get->role == 'Store Manager') {
                $repair->request_to = 3;
            }
            else {
                $repair->request_to = 12;
            }

            $repair->save();

            return redirect()->route('repair.index')->with([
                'status' => 'success',
                'message' => 'Repair Request Added successfully!'
            ]);
        }
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
