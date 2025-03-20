<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StoreSetupController extends Controller
{
    public function list()
    {

        $list = DB::table('set_up')->get();

        // dd($list);
        return view('setup.list',['list'=>$list]);
    }

    public function create()
    {
        return view('setup.add');
    }

    public function profile($tab = null,Request $req)
    {
         $pro = DB::table('set_up')->where('id',$req->id)->first();

         $set_list = DB::table('e_setup')->where('set_id',$req->id)->get();

        //  $cmp = DB::table('e_setup')->where('cat','')->where('sub','')

        // if($tab=='details'){

        // }

        // dd($set_list);

        return view('setup.profile',['tab'=>$tab,'pro'=> $pro,'list'=>$set_list]);
    }

    public function store(Request $req)
    {
        $user = Auth::user();

        $ins = DB::table('set_up')->insert([
            'st_name'=>$req->storename,
            'st_add'=>$req->address,
            'st_city'=>$req->city,
            'st_state'=>$req->state,
            'st_pin'=>$req->pincode,
            'st_loc'=>$req->geolocation,
            'status'=>'Active',
            'c_by'=>$user->id,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        $list = DB::table('set_up')->get();

        if($ins){
            return response()->view('setup.list',['status'=>'sucess','message'=>'Store set Up added Successfully','list'=>$list]);
        }else{
            return response()->view('setup.list',['status'=>'Failed','message'=>'Store set Up Failed to add']);
        }
    }


    public function set_list_store(Request $req,)
    {
        $user = Auth::user();

        $ins = DB::table('e_setup')->insertGetId([
            'set_id'=>$req->set_id,
            'cat'=>$req->setupcat,
            'sub'=>$req->setupsubcat,
            'remark'=>$req->remarks,
            'status'=>'Active',
            'c_by'=>$user->id,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        $path = 'assets/images/setup_docs/';

        if ($req->hasFile('attachment')) {
            $cer_file = $req->file('attachment');
            // $name = date('y') . '-' . Str::upper(Str::random(8)) . '.' . $file->getClientOriginalExtension();
                $cer_ext = $cer_file->getClientOriginalExtension();
                $cer_name = uniqid('setup_file_') . '.' . $cer_ext; // Generate a unique filename

            $cer_file->move($path, $cer_name);

            $f_path = $path.$cer_name;


        }

        if (!empty($f_path)) {
            $up_file = DB::table('e_setup')->where('id', $ins)
                ->update(['file'=>$f_path]);
        }

        if($ins){
            return back()->with(['status'=>'sucess','message'=>'Store set Up added Successfully']);
        }else{
            return back()->with(['status'=>'Failed','message'=>'Store set Up Failed to add']);
        }
    }

    public function edit(string $id)
    {
        //
    }

    public function setlist_update(Request $req)
    {
        $up = DB::table('e_setup')->where('id',$req->e_id)
        ->update([
            'status'=>$req->status,
            's_remark'=>$req->s_remark
        ]);

        if($up){
            return back()->with(['status'=>'sucess','message'=>'Store set Up Updated Successfully']);
        }else{
            return back()->with(['status'=>'Failed','message'=>'Store set Up Failed to Update']);
        }
    }

    public function store_new(Request $req)
    {

        $max_id = DB::table('stores')->max('id');

        $store_no = 'STORE' . str_pad($max_id + 1, 2, '0', STR_PAD_LEFT);

       $up = DB::table('set_up')->where('id',$req->id)->update(['status'=>'Complete','st_code'=>$store_no]);


       if($up){
         return redirect('store-add')->with(['status'=>'sucess']);
       }

    }
}
