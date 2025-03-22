<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Repair;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseService;
use App\Models\Notification;


class RepairController extends Controller
{
    public function index()
    {

        $rep = DB::table('maintain_req')->where('maintain_req.c_by',auth()->user()->id)
        ->leftJoin('users','users.id','=','maintain_req.req_to')
        ->leftJoin('categories','categories.id','=','maintain_req.cat')
        ->leftJoin('sub_categories','sub_categories.id','=','maintain_req.sub')
        ->select('maintain_req.*','users.name','categories.category','sub_categories.subcategory','maintain_req.status as m_status')
        ->get();
// dd($rep);
        return view('repair.list',['rep'=>$rep]);
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

        $ins = DB::table('maintain_req')->insertGetId([
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


                $req_token  = DB::table('users')->whereid('id',$req->request_to)->first();

                if (!is_null($req_token->device_token)) {
                    $taskTitle ="Maintenance  Request";
                    $taskBody = auth()->user()->name . "Request For Maintenance  Request";

                    $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

                    Notification::create([
                        'user_id' => $req_token->id ?? 0,
                        'noty_type' => 'Maintenance',
                        'type_id' => $ins
                    ]);
                } // notification end

        $rep = DB::table('maintain_req')->where('maintain_req.c_by',auth()->user()->id)
        ->leftJoin('users','users.id','=','maintain_req.req_to')
        ->leftJoin('categories','categories.id','=','maintain_req.cat')
        ->leftJoin('sub_categories','sub_categories.id','=','maintain_req.sub')
        ->select('maintain_req.*','users.name','categories.category','sub_categories.subcategory','maintain_req.status as m_status')
        ->get();

        if($ins){
            return response()->view('repair.list',['rep'=> $rep,'status'=>'sucess','message'=>'Maintenance Request added Successfully']);
        }else{
            return response()->view('repair.list',['rep'=> $rep,'status'=>'Failed','message'=>'Maintenance Request Failed to add']);
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
