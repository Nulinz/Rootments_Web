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

class ApproveController extends Controller
{
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


// if ($role_get->role_id == 12) {
//     $leave_count = DB::table('leaves')
//         ->where(function ($query) use ($user) {
//             $query->where('request_status', 'Pending')
//                   ->orWhere('created_by', $user->id);
//         })->where('request_to', 12)->count();

//     $repair_count = DB::table('repairs')
//         ->where(function ($query) use ($user) {
//             $query->where('request_status', 'Pending')
//                   ->orWhere('created_by', $user->id);
//         })->count();

//     $transfer_count = DB::table('transfers')
//         ->where(function ($query) use ($user) {
//             $query->where('request_status', 'Pending')
//                   ->orWhere('created_by', $user->id);
//         })->count();

//     $resign_count = DB::table('resignations')
//         ->where(function ($query) use ($user) {
//             $query->where('request_status', 'Pending')
//                   ->orWhere('created_by', $user->id);
//         })->count();

//     $recruit_count = DB::table('recruitments')
//         ->where(function ($query) use ($user) {
//             $query->where('request_status', 'Pending')
//                   ->orWhere('created_by', $user->id);
//         })->count();
// } elseif ($role_get->role_id == 3) {
//     $leave_count = DB::table('leaves')
//         ->where(function ($query) use ($user) {
//             $query->where('esculate_status', 'Pending')
//                   ->orWhere('created_by', $user->id);
//         })->count();

//     $repair_count = DB::table('repairs')
//         ->where(function ($query) use ($user) {
//             $query->where('esculate_status', 'Pending')
//                   ->orWhere('created_by', $user->id);
//         })->count();

//     $transfer_count = DB::table('transfers')
//         ->where(function ($query) use ($user) {
//             $query->where('esculate_status', 'Pending')
//                   ->orWhere('created_by', $user->id);
//         })->count();

//     $resign_count = DB::table('resignations')
//         ->where(function ($query) use ($user) {
//             $query->where('esculate_status', 'Pending')
//                   ->orWhere('created_by', $user->id);
//         })->count();

//     $recruit_count = DB::table('recruitments')
//         ->where(function ($query) use ($user) {
//             $query->where('esculate_status', 'Pending')
//                   ->orWhere('created_by', $user->id);
//         })->count();
// }





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

            // $leave = DB::table('leaves')
            // ->leftJoin('users','users.id','=','leaves.user_id')
            // ->leftJoin('roles','roles.id','=','users.role_id')
            // ->where('request_status','Escalate')
            // ->Where('esculate_status','Pending')
            // ->where(function($query) use ($user) {
            //     $query->where('request_to', $user->id)
            //           ->orWhere('esculate_to', $user->id);
            // })

            // ->select('users.name','users.emp_code','roles.role','roles.role_dept','leaves.request_status','leaves.request_type','leaves.id')
            // ->get();

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


            //     $l_id =[];
            // foreach($leave as $lv){
            //         $l_id = $lv->id;
            // }

            // $list =DB::table('leaves')
            // ->leftJoin('users','users.id','=','leaves.request_to')
            // ->leftJoin('roles','roles.id','=','users.role_id')

        }




        //  return $leave;
          return view('approve.leavelist', ['leave' => $leave,'hr_list'=>$hr]);

             // $storeMembers = DB::table('users')->where('store_id', $user->store_id)->pluck('id')->toArray();

        // $role_get = DB::table('roles')
        //     ->join('users', 'users.role_id', '=', 'roles.id')
        //     ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
        //     ->where('users.id', $user->id)
        //     ->first();

        // if ($role_get) {
        //     $leave = DB::table('leaves')
        //         ->join('users', 'users.id', '=', 'leaves.user_id')
        //         ->where(function ($query) use ($role_get) {
        //             $query->where('leaves.request_to', $role_get->role_id)
        //                 ->orWhere('leaves.esculate_to', $role_get->role_id);
        //         })
        //         ->whereIn('users.id', $storeMembers)
        //         ->select('leaves.id as l_id','leaves.request_type')
        //         ->get();
        // }
    }


    public function repairindex()
    {
        $user_id = Auth::user()->id;

        $repair=DB::table('repairs')
        ->leftjoin('stores','stores.id','=','repairs.store_code')
        ->select('repairs.*','stores.store_code')
        ->where('repairs.created_by',$user_id)
        ->get();

        return view('approve.repairlist',['repair'=>$repair]);
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



        // $role_get = DB::table('roles')
        //     ->join('users', 'users.role_id', '=', 'roles.id')
        //     ->where('users.id', $user->id)
        //     ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
        //     ->first();

        // $resignation = collect();

        // if ($role_get) {
        //     $resignation = DB::table('resignations')
        //         ->leftJoin('stores', 'stores.id', '=', 'resignations.store_id')
        //         ->leftJoin('users', 'users.id', '=', 'resignations.emp_id')
        //         ->select('resignations.*', 'stores.store_name', 'users.emp_code')
        //         ->where(function ($query) use ($role_get) {
        //             $query->where('resignations.request_to', $role_get->role_id)
        //                   ->orWhere('resignations.esculate_to', $role_get->role_id);
        //         })
        //         ->get();
        // // }

        return view('approve.reginlist',['resgination'=>$resignation,'hr_list'=>$hr]);
    }


    public function recruitindex()
    {
        $user = auth()->user();

    $role_get = DB::table('roles')
        ->join('users', 'users.role_id', '=', 'roles.id')
        ->where('users.id', $user->id)
        ->select('roles.id as role_id', 'roles.role', 'roles.role_dept')
        ->first();

    $rec = collect();

    if ($role_get) {
        $rec = DB::table('recruitments')
            ->leftJoin('stores', 'stores.id', '=', 'recruitments.store_id')
            ->leftJoin('recruitment_lists', 'recruitment_lists.rect_id', '=', 'recruitments.id')
            ->leftJoin('roles', 'recruitment_lists.role_id', '=', 'roles.id')
            ->select(
                'recruitments.id',
                'recruitments.store_id',
                'stores.store_name',
                'recruitments.res_date',
                'recruitments.status',
                'recruitments.request_status',
                'recruitments.esculate_status',
                'stores.store_code',
                DB::raw('GROUP_CONCAT(roles.role) as roles'),
                DB::raw('GROUP_CONCAT(recruitment_lists.vat_count) as vat_counts')
            )
            ->where(function ($query) use ($user, $role_get) {
                $query->where('recruitments.created_by', $user->id)
                      ->orWhere('recruitments.request_to', $role_get->role_id);
            })
            ->groupBy('recruitments.id', 'recruitments.store_id', 'recruitments.res_date', 'stores.store_name', 'stores.store_code', 'recruitments.status')
            ->get();
    }

    return view('approve.recruitlist', ['rec' => $rec]);
}

    public function updateLeave(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);


            //  $user = auth()->user();

            //  $leave = Leave::findOrFail($request->id);

            if($request->status == 'Escalate'){

                DB::table('leaves')
                ->where('id',$request->id)
                ->update([
                    'esculate_to'=>$request->hr,
                    'request_status'=>$request->status,
                    'status'=>$request->status
                ]);
                //  $notification = Notification::create([
                //             'user_id' => $leave->user_id,
                //             'noty_type' => 'leave',
                //             'type_id' => $request->id,
                //         ]);
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
            }

            $user_id = Auth::user();

             $req_token  = DB::table('users')->where('id',$status->user_id)->first();

             if ($req_token->device_token) {
                     $taskTitle = "Leave Request Updated";
                    $taskBody = $user_id->name. " Leave Request Updated to".$request->status ;

                    $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

                    Notification::create([
                        'user_id' => $status->user_id,
                        'noty_type' => 'leave',
                        'type_id' => $request->id
                    ]);
            } // notification end


            return response()->json(['message' => 'Leave updated successfully!'], 200);

                // $leave->status = $request->status;



            // if ($user->role_id == 12) {
            //     $leave->request_status = $request->status;
            //     if($request->status == 'Rejected')
            //     {
            //          $leave->status = $request->status;
            //     }
            // } elseif ($user->role_id == 3) {
            //     $leave->esculate_status = $request->status;
            //     $leave->status = $request->status;

            //      $notification = Notification::create([
            //                 'user_id' => $leave->user_id,
            //                 'noty_type' => 'leave',
            //                 'type_id' => $request->id,
            //             ]);

            // } else {
            //     return response()->json(['error' => 'Unauthorized action.'], 403);
            // }

            // $leave->save();

            // return response()->json(['message' => 'Leave updated successfully!'], 200);

    }



    public function updaterepair(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        try {
            $repair = Repair::findOrFail($request->id);

            $repair->status = $request->status;
            $repair->save();

            return response()->json(['message' => 'Repair updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update Repaair.'], 500);
        }
    }

    public function updateresgin(Request $request)
    {
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
            //  $notification = Notification::create([
            //             'user_id' => $leave->user_id,
            //             'noty_type' => 'leave',
            //             'type_id' => $request->id,
            //         ]);
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
        }

        return response()->json(['message' => 'Resgination updated successfully!'], 200);




            // try {
            //     $user = auth()->user();

            //     $resgin = Resignation::findOrFail($request->id);

            //     if ($user->role_id == 12) {
            //         $resgin->request_status = $request->status;
            //         if($request->status == 'Rejected')
            //     {
            //          $resgin->status = $request->status;
            //     }
            //     } elseif ($user->role_id == 3) {
            //         $resgin->esculate_status = $request->status;
            //         $resgin->status = $request->status;
            //             $notification = Notification::create([
            //                 'user_id' => $resgin->emp_id,
            //                 'noty_type' => 'resignation',
            //                 'type_id' => $request->id,
            //             ]);
            //     } else {
            //         return response()->json(['error' => 'Unauthorized action.'], 403);
            //     }

        //         $resgin->save();

        //     return response()->json(['message' => 'Resgination updated successfully!'], 200);
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Failed to update Resgination.'], 500);
        // }
    }

    public function updaterecuirt(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        try {
            $user = auth()->user();

            $recruit = Recruitment::findOrFail($request->id);

           if ($user->role_id == 3) {
                $recruit->esculate_status = $request->status;
                $recruit->status = $request->status;
                // $notification = Notification::create([
                //             'user_id' => $recruit->emp_id,
                //             'noty_type' => 'recuriment',
                //             'type_id' => $request->id,
                //         ]);

            } else {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }


            $recruit->save();

            return response()->json(['message' => 'Recruitment updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update Recruitment.'], 500);
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
