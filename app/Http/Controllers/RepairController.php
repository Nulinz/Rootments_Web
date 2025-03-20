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
        $cat =  DB::table('categories')->whereIn('id',[17,18,19,20,21,22,23,24,25,26])->get();

        $req = DB::table('users')->whereIn('role_id',[2,10,11])->get();

        return view('repair.add',['cat'=>$cat,'req_to'=>$req]);

    }

    public function store(Request $req)
    {

        $user = Auth::user();

        $ins = DB::table('maintain_req')->insert([
            'title'=>$req->title,
            'cat'=>$req->category,
            'sub'=>$req->subcategory,
            'req_date'=>$req->repair_date,
            'desp'=>$req->desp,
            'req_to'=>$req->request_to,
            'req_status'=>'Pending',
            'status'=>'Pending',
            'c_by'=>$user->id,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        $path = 'assets/images/Repair/';

        if ($req->hasFile('repair_file')) {
            $cer_file = $req->file('repair_file');
            // $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
                $cer_ext = $cer_file->getClientOriginalExtension();
                $cer_name = uniqid('repair_file_') . '.' . $cer_ext; // Generate a unique filename

            $cer_file->move($path, $cer_name);

            $f_path = $path.$cer_name;


        }

        if (!empty($f_path)) {
            $up_file = DB::table('maintain_req')->where('id', $ins)
                ->update(['file'=>$f_path]);
        }

        // $list = DB::table('set_up')->get();

        if($ins){
            return response()->view('repair.list',['status'=>'sucess','message'=>'Maintenance Request added Successfully']);
        }else{
            return response()->view('repair.list',['status'=>'Failed','message'=>'Maintenance Request Failed to add']);
        }

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
