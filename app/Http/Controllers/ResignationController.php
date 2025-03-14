<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Resignation;
use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseService;
use App\Models\Notification;

class ResignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
    {
        $user_id = Auth::user()->id;

        $resgination = DB::table('resignations')
        ->where('resignations.created_by',$user_id)
        ->leftjoin('users','users.id','=','resignations.emp_id')
        // ->leftJoin('resignations as rs','rs.emp_id','=', 'users.id')
        ->select('resignations.*','users.emp_code')

        ->get();

        // dd($resgination);

        foreach($resgination as $res){

            $resing_tbl = DB::table('resign_list')->where('res_id', $res->id)->latest()->first();

            // If a record is found, assign the status to the res_status field
            // if ($resing_tbl) {
                $res->res_status = $resing_tbl->status ?? null;
                $res->res_formal = $resing_tbl->formality ?? null;
            // }else{

            // }

        }

        //  dd($resgination);



        return view('resgination.list',['resgination'=>$resgination]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $user_id = Auth::user();

        // if($user_id->role_id >= 13 && $user_id->role_id <= 19){

        // } else if(!hasAccess($user_id->role_id,'leave')){

        //     $dept = DB::table('roles')->where('id',$user_id->role_id)->select('role_dept')->first();

        //     switch($dept->role_dept) {
        //         case 'HR':
        //             $arr = 3;
        //             break;
        //         case 'Finance':
        //             $arr = 7;
        //             break;
        //         case 'Maintenance':
        //             $arr = 30;
        //             break;
        //         case 'Warehouse':
        //             $arr = 37;
        //             break;
        //         case 'Purchase':
        //             $arr = 41;
        //             break;

        //     }

        // }else{

        // }
        $hr = DB::table('users')->whereIn('role_id', [3,4,5])->select('users.id','users.name')->get();

        $user_list =Auth::user();

        return view('resgination.add',['hr_list'=>$hr,'user_log'=>$user_list]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = auth()->user();

          $role_get = DB::table('roles')
            ->leftJoin('users', 'users.role_id', '=', 'roles.id')
            ->select('roles.id', 'roles.role', 'roles.role_dept')
            ->where('users.id', $user_id->id)
            ->first();

        if ($role_get) {

            $resgination = new Resignation();
            $resgination->emp_id =$request->emp_id;
            $resgination->emp_name =$request->emp_name;
            $resgination->store_id =$request->store_id;
            $resgination->loc =$request->loc;
            $resgination->res_date =$request->res_date;
            $resgination->res_reason =$request->res_reason;
            $resgination->created_by=$user_id->id;

            $role = $role_get->role;
            $role_dept = $role_get->role_dept;

            if($user_id->role_id >= 13 && $user_id->role_id <= 19){

                $store_man = DB::table('users')->where('store_id',$user_id->store_id)->where('role_id',12)->first();
                        $resgination->request_to = $store_man->id ?? 2;
                         $req_to = $store_man->id ?? 2;
                        $req_token  = DB::table('users')->where('id',$store_man->id ?? 2)->first();
            }
            else if(!hasAccess($user_id->role_id,'leave')){

                $dept = DB::table('roles')->where('id',$user_id->role_id)->select('role_dept')->first();

                switch($dept->role_dept) {
                    case 'HR':
                        $arr = 3;
                        break;
                    case 'Finance':
                        $arr = 7;
                        break;
                    case 'Maintenance':
                        $arr = 30;
                        break;
                    case 'Warehouse':
                        $arr = 37;
                        break;
                    case 'Purchase':
                        $arr = 41;
                        break;

                }

                $arr1 = DB::table('users')->where('role_id',$arr)->select('id')->first();

                $resgination->request_to = $arr1->id;
                $req_to = $arr1->id;
                $req_token  = DB::table('users')->where('id',$arr1->id)->first();

            } else{
                    $resgination->request_to = $request->request_to;
                     $req_to = $request->request_to;
                    $req_token  = DB::table('users')->where('id',$request->request_to)->first();
                }


            $resgination->save();


            // dd($req_token->device_token);


            if ($req_token->device_token) {
                    $taskTitle = "Resignation Request";
                    $taskBody = $user_id->name. "Requested for Resignation";

                    $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

                    Notification::create([
                        'user_id' => $req_to,
                        'noty_type' => 'resignation',
                        'type_id' => $resgination->id
                    ]);
            } // notification end

        }

        return redirect()->route('resignation.index')->with([
            'status' => 'success',
            'message' => 'Resgination Request Added successfully!',
        ]);
    }

    public function updateEscalate(Request $request)
    {
        DB::table('resignations')
            ->where('id', $request->id)
            ->update(['esculate_to' => 3, 'updated_at' => now()]);

        return response()->json(['message' => 'Escalated successfully!']);
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
