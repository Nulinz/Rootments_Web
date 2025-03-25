<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Leave;
use App\Models\Repair;
use App\Models\Resignation;
use App\Models\Recruitment;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseService;
use App\Http\Controllers\trait\common;

class ApproveController extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */
    public function index()
{
   $user = auth()->user();

if (!$user) {
    return response()->json(['error' => 'User not authenticated'], 401);
}

$role_get = DB::table('roles')
    ->join('users', 'users.role_id', '=', 'roles.id')
    ->where('users.id', $user->id)
    ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
    ->first();

if (!$role_get) {
    return response()->json(['error' => 'Role not found'], 404);
}

$leave_count = $repair_count = $transfer_count = $resign_count = $recruit_count = 0;



    return view('approve.approvelist', [
        'leave_count' => $leave_count ?? 0,
        'repair_count' => $repair_count ?? 0,
        'transfer_count' => $transfer_count ?? 0,
        'resign_count' => $resign_count ?? 0,
        'recruit_count' => $recruit_count ?? 0
    ]);
}

    public function leaveindex()
    {


        $user = auth()->user();

        $hr = DB::table('users')->whereIn('role_id', [3,4,5])->select('users.id','users.name')->get();

        $arr = [3,4,5];

        if(in_array($user->role_id,$arr)){



            $leave = DB::table('leaves')
            ->leftJoin('users', 'users.id', '=', 'leaves.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('stores', 'stores.id', '=', 'users.store_id')
            ->where(function($query) {
                $query->where('leaves.request_status', 'Pending')
                      ->orWhere('leaves.request_status', 'Escalate');
            })
            ->where('leaves.esculate_status', 'Pending')
            ->where(function($query) use ($user) {
                $query->where('leaves.request_to', $user->id)
                      ->orWhere('leaves.esculate_to', $user->id);
            })
            ->select('leaves.id', 'users.name', 'users.emp_code', 'roles.role', 'roles.role_dept', 'leaves.request_status', 'leaves.request_type','stores.store_name','leaves.start_date','leaves.end_date','leaves.start_time','leaves.end_time')
            ->get();


        }else{

            $leave = DB::table('leaves')
            ->join('users', 'users.id', '=', 'leaves.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('stores', 'stores.id', '=', 'users.store_id')
            ->where('leaves.request_to', $user->id)
            ->where('leaves.request_status', 'Pending')
            ->select('users.name','users.emp_code','roles.role','roles.role_dept','leaves.request_status','leaves.request_type','leaves.id','stores.store_name','leaves.start_date','leaves.end_date','leaves.start_time','leaves.end_time')
            ->get();

        }




        //  return $leave;
          return view('approve.leavelist', ['leave' => $leave,'hr_list'=>$hr]);


    }


    public function repairindex()
    {
        $user_id = Auth::user()->id;

        $user = auth()->user();


        if($user->role_id==30){
            $status = 'esculate_status';
            $to = 'esculate_to';
        }else{
            $status = 'req_status';
            $to = 'req_to';
        }

            $repair = DB::table('maintain_req')
            ->join('users', 'users.id', '=', 'maintain_req.c_by')
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('categories as cat','cat.id','=','maintain_req.cat')
            ->leftJoin('sub_categories as sub','sub.id','=','maintain_req.sub')
            ->where('maintain_req.'.$to, $user->id)
            ->where('maintain_req.'.$status, 'Pending')
            ->select('users.name','users.emp_code','sub.subcategory','cat.category','maintain_req.*','maintain_req.id as rep_id')
            ->get();

        // }

        // dd($repair);



        return view('approve.repairlist',['repair'=> $repair ]);
    }

    public function transferindex()
    {
        $user = auth()->user();

        $role_get = DB::table('roles')
            ->join('users', 'users.role_id', '=', 'roles.id')
            ->where('users.id', $user->id)
            ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
            ->first();

        $transfer = collect();

        if ($role_get) {
            $transfer = DB::table('transfers')
                ->leftJoin('users', 'users.id', '=', 'transfers.emp_id')
                ->leftJoin('stores as from_stores', 'from_stores.id', '=', 'transfers.fromstore_id')
                ->leftJoin('stores as to_stores', 'to_stores.id', '=', 'transfers.tostore_id')
                ->leftJoin('users as created_by_user', 'created_by_user.id', '=', 'transfers.created_by')
                ->leftJoin('stores as created_by_store', 'created_by_store.id', '=', 'created_by_user.store_id')
                ->where(function ($query) use ($role_get) {
                    $query->where('transfers.request_to', $role_get->role_id)
                        ->orWhere('transfers.esculate_to', $role_get->role_id);
                })
                ->select(
                    'transfers.*',
                    'users.emp_code',
                    'users.name',
                    'users.id as user_id',
                    'from_stores.store_name as from_store_name',
                    'to_stores.store_name as to_store_name',
                    'created_by_user.name as created_by_name',
                    'created_by_user.emp_code as created_by_emp_code',
                    'created_by_store.store_name as created_by_store_name',
                    'created_by_store.store_code as created_by_store_code'
                )
                ->get();
        }
        return view('approve.transferlist',['transfer'=>$transfer]);
    }

    public function resginindex()
    {
        $user = auth()->user();

        $hr = DB::table('users')->whereIn('role_id', [3,4,5])->select('users.id','users.name')->get();

        $arr = [3,4,5];

        if(in_array($user->role_id,$arr)){

        $resignation = DB::table('resignations')
        ->leftJoin('users', 'users.id', '=', 'resignations.emp_id')
        ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
        ->leftJoin('stores', 'stores.id', '=', 'users.store_id')
        ->where(function($query) {
            $query->where('resignations.request_status', 'Pending')
                  ->orWhere('resignations.request_status', 'Escalate');
        })
        ->where('resignations.esculate_status', 'Pending')
        ->where(function($query) use ($user) {
            $query->where('resignations.request_to', $user->id)
                  ->orWhere('resignations.esculate_to', $user->id);
        })
        ->select('resignations.id', 'users.name', 'users.emp_code', 'roles.role', 'roles.role_dept', 'resignations.request_status','stores.store_name','resignations.res_date','resignations.res_reason')
        ->get();
    }else{

        $resignation = DB::table('resignations')
        ->join('users', 'users.id', '=', 'resignations.emp_id')
        ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
        ->leftJoin('stores', 'stores.id', '=', 'users.store_id')
        ->where('resignations.request_to', $user->id)
        ->where('resignations.request_status', 'Pending')
        ->select('users.name','users.emp_code','roles.role','roles.role_dept','resignations.request_status','resignations.id','stores.store_name','resignations.res_date','resignations.res_reason')
        ->get();
    }


        return view('approve.reginlist',['resgination'=>$resignation,'hr_list'=>$hr]);
    }


    public function recruitindex()
    {
        $user = auth()->user();

        $rec = DB::table('recruitments as rc')
        ->leftJoin('roles', 'rc.role', '=', 'roles.id')
        ->leftJoin('users as uc', 'uc.id', '=', 'rc.c_by')
        ->where('rc.status','Pending')
        ->select(
            'rc.id',
            'rc.dept',
            'rc.role',
            'rc.loc',
            'rc.loc',
            'rc.vacancy',
            'rc.request_to',
            'rc.exp',
            'rc.description',
            'rc.status',
            'roles.role',
            'rc.res_date',
            'uc.name'

        )
        ->get();

        // dd($rec);

    return view('approve.recruitlist', ['rec' => $rec]);
}

    public function updateLeave(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);


            //  $user = auth()->user();

             $leave = Leave::findOrFail($request->id);

            if($request->status == 'Escalate'){

                DB::table('leaves')
                ->where('id',$request->id)
                ->update([
                    'esculate_to'=>$request->hr,
                    'request_status'=>$request->status,
                    'status'=>$request->status
                ]);

                $user_id = Auth::user();

                $leave_token  = DB::table('users')->whereIn('id',[$request->hr,$leave->created_by])->get();

                foreach($leave_token as $req_token){

                    if (!is_null($req_token->device_token)) {
                            $taskTitle = "Leave Request";
                            $taskBody = $user_id->name. " Leave Request Updated to".$request->status ;

                            $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

                            Notification::create([
                                'user_id' => $req_token->id,
                                'noty_type' => 'leave',
                                'type_id' => $request->id,
                                'title'=> $taskTitle,
                                'body'=> $taskBody,
                                'c_by'=>$user_id->id
                            ]);
                    } // notification end
                } // foreach end
            }else{

                $status =  DB::table('leaves')->where('id',$request->id)->first();

                if($status->request_status=='Pending'){
                    $col = 'request_status';
                }else{
                    $col = 'esculate_status';
                }

                DB::table('leaves')
                ->where('id',$request->id)
                ->update([
                    $col=>$request->status,
                    'status'=>$request->status
                ]);

                $user_id = Auth::user();

                $req_token  = DB::table('users')->where('id',$status->created_by)->first();


                if ($req_token->device_token) {
                    $taskTitle = "Leave Request";
                   $taskBody = $user_id->name. " Leave Request Updated to".$request->status ;

                   $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

                   Notification::create([
                       'user_id' => $status->created_by,
                       'noty_type' => 'leave',
                       'type_id' => $request->id,
                       'title'=> $taskTitle,
                        'body'=> $taskBody,
                        'c_by'=>$user_id->id
                   ]);
                } // notification end


            }




            return response()->json(['message' => 'Leave updated successfully!'], 200);


    }



    public function updaterepair(Request $request)
    {
        $request->validate([
            'rep_id' => 'required',
            'status' => 'required',
        ]);

        $req_token  = DB::table('users')->where('role_id',30)->first();

        if($request->status == 'Escalate'){

            DB::table('maintain_req')
            ->where('id',$request->rep_id)
            ->update([
                'esculate_to'=>$req_token->id,
                'esculate_status'=>'Pending',
                'req_status'=>$request->status,
                'status'=>$request->status
            ]);

            $user_id = Auth::user();

            $c_by = DB::table('maintain_req')->where('id',$request->rep_id)->first();

            $two_not = DB::table('users')->whereIn('id',[$c_by->c_by,$req_token->id])->get();

            foreach($two_not  as $two_token){

                    if (!is_null($two_token->device_token)) {
                            $taskTitle = "Maintenance Request";
                        $taskBody = auth()->user()->name . " Maintenance Request Updated to".$request->status ;

                        $response = app(FirebaseService::class)->sendNotification($two_token->device_token,$taskTitle,$taskBody);

                        Notification::create([
                            'user_id' =>  $two_token->id,
                            'noty_type' => 'Maintenance',
                            'type_id' => $request->rep_id,
                            'title'=> $taskTitle,
                            'body'=> $taskBody,
                            'c_by'=>auth()->user()->id
                        ]);
                } // notification end

            } // foreach end///.......

            // dd($two_not);

        }else{

            $status =  DB::table('maintain_req')->where('id',$request->rep_id)->first();

            if($status->req_status=='Pending'){
                $col = 'req_status';
            }else{
                $col = 'esculate_status';
            }

            DB::table('maintain_req')
            ->where('id',$request->rep_id)
            ->update([
                $col=>$request->status,
                'status'=>$request->status
            ]);

            $user_id = Auth::user();

            $req_token  = DB::table('users')->where('id',$status->c_by)->first();


            if (!is_null($req_token->device_token)) {
                $taskTitle = "Maintenance Request";
               $taskBody = auth()->user()->name. " Maintenance Request Updated to".$request->status ;

               $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

               Notification::create([
                   'user_id' => $status->c_by,
                   'noty_type' => 'Maintenance',
                   'type_id' => $request->rep_id,
                   'title'=> $taskTitle,
                    'body'=> $taskBody,
                    'c_by'=>auth()->user()->id
               ]);
       } // notification end


       if($request->status == 'Approved'){
            return redirect()->route('maintain.task',['id'=>$request->rep_id])->with(['status'=>'success','message' => 'Maintenance Request updated successfully!']);

       }
       else{
        return back()->with(['status'=>'success','message' => 'Maintenance Request updated successfully!']);
       }





        }


         return back()->with(['status'=>'success','message' => 'Maintenance Request updated successfully!']);


    }

    public function updateresgin(Request $request)
    {
        $user_id = Auth::user();

        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        if($request->status == 'Escalate'){

            DB::table('resignations')
            ->where('id',$request->id)
            ->update([
                'esculate_to'=>$request->hr,
                'request_status'=>$request->status,
                'status'=>$request->status
            ]);
                $req_token  = DB::table('users')->where('id',$request->hr)->first();

                if ($req_token->device_token) {
                    $taskTitle = "Resignation Request";
                    $taskBody = $user_id->name. "Esculated for Resignation";

                    $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

                    Notification::create([
                        'user_id' => $request->hr,
                        'noty_type' => 'resignation',
                        'type_id' => $request->id,
                        'title'=> $taskTitle,
                        'body'=> $taskBody,
                        'c_by'=>auth()->user()->id
                    ]);
            } // notification end

            // dd($req_token->device_token);
        }else{

            $status =  DB::table('resignations')->where('id',$request->id)->first();

            if($status->request_status=='Pending'){
                $col = 'request_status';
            }else{
                $col = 'esculate_status';
            }

            DB::table('resignations')
            ->where('id',$request->id)
            ->update([
                $col=>$request->status,
                'status'=>$request->status
            ]);


            $req_token  = DB::table('users')->where('id',$status->emp_id)->first();

            if ($req_token->device_token) {
                $taskTitle = "Resignation Request";
                $taskBody = $user_id->name ."-" .$request->status . " for Resignation";

                $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

                Notification::create([
                    'user_id' => $status->emp_id,
                    'noty_type' => 'resignation',
                    'type_id' => $request->id,
                    'title'=> $taskTitle,
                    'body'=> $taskBody,
                    'c_by'=>auth()->user()->id
                ]);
        } // notification end

        }




        return response()->json(['message' => 'Resgination updated successfully!','token'=>$req_token->device_token], 200);


    }

    public function updaterecuirt(Request $request)
    {
        $request->validate([
            'RecruitId' => 'required',
            'status' => 'required',
        ]);

        try {
            $user = auth()->user();

            $recruit = Recruitment::findOrFail($request->RecruitId);

                $recruit->status = $request->status;

                $recruit->save();

                //  Notification::create([
                //             'user_id' => $recruit->c_by,
                //             'noty_type' => 'recuriment',
                //             'type_id' => $request->id,
                //             'title'=> $taskTitle,
                //             'body'=> $taskBody,
                //             'c_by'=>auth()->user()->id
                //         ]);


            return response()->json(['message' => 'Recruitment updated successfully!'], 200);
        } catch (\Exception $e) {

            dd($e);
            // return response()->json(['error' => 'Failed to update Recruitment.'], 500);
        }
    }

  public function updatetransfer(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:transfers,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:Approved,Rejected',
        ]);

        try {
            $user = auth()->user();

            $transfer = Transfer::findOrFail($request->id);

            if ($user->role_id == 12) {
                $transfer->request_status = $request->status;
                  if($request->status == 'Rejected')
                {
                     $transfer->status = $request->status;
                }
            } elseif ($user->role_id == 3) {
                $transfer->esculate_status = $request->status;
                $transfer->status = $request->status;
                $notification = Notification::create([
                            'user_id' => $transfer->emp_id,
                            'noty_type' => 'transfer',
                            'type_id' => $request->id,
                        ]);

            } else {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }

            $transfer->save();

            if ($request->status === 'Approved') {
                $user = User::find($request->user_id);
                if ($user) {
                    $user->store_id = $transfer->tostore_id;
                    $user->save();
                }
            }

            return response()->json(['message' => 'Transfer updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update transfer.'], 500);
        }


    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
