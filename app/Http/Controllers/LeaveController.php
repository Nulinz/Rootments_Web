<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Leave;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Log;

use Google\Client;
use Google\Service\FirebaseCloudMessaging;
use Google\Service\FirebaseCloudMessaging\Message;
use Google\Service\FirebaseCloudMessaging\Notification as FcmNotification;
use Google\Service\FirebaseCloudMessaging\SendMessageRequest;
use App\Http\Controllers\trait\common;


class LeaveController extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;

        $leave=DB::table('leaves')
        ->leftjoin('users','leaves.user_id','=','users.id')
        ->select('leaves.*','users.name','users.emp_code')
        ->where('leaves.created_by',$user_id)
        ->get();

        return view('leave.list',['leave'=>$leave]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {



        $show = $this->role_dept();

        $list = DB::table('users')
        ->whereIn('role_id', $show)
        ->when($show == [12], function ($query) {
            return $query->where('store_id', auth()->user()->store_id);
        })
        ->select('users.id', 'users.name')
        ->get();

        // dd($show);


        return view('leave.add',['list'=>$list]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user_id = auth()->user();


            $leave = new Leave();
            $leave->start_date = $request->start_date;
            $leave->end_date = $request->end_date;
            $leave->request_type = $request->request_type;
            $leave->reason = $request->reason;
            $leave->start_time = date("h:i",strtotime($request->start_time));
            $leave->end_time = date("h:i",strtotime($request->end_time));
            $leave->user_id = $user_id->id;
            $leave->request_to = $request->request_to;
            $leave->created_by = $user_id->id;
            $leave_save = $leave->save();

            $req_token  = DB::table('users')->where('id',$request->request_to)->first();

                if (!is_null($req_token->device_token)) {

                    $role_get = DB::table('roles')->where('id', $user_id->role_id)->first();
                    $taskTitle = $request->request_type." Request";
                    $taskBody = $user_id->name."[".$role_get->role."]". " Requested for " . $request->request_type;

                    $response = app(FirebaseService::class)->sendNotification($req_token->device_token,$taskTitle,$taskBody);

                    Notification::create([
                        'user_id' => $req_token->id,
                        'noty_type' => 'leave',
                        'type_id' => $leave->id,
                        'title'=> $taskTitle,
                        'body'=> $taskBody,
                        'c_by'=>$user_id->id
                    ]);
                } // notification end



            return redirect()->route('leave.index')->with([
                'status' => $leave_save ? 'success' : 'failed',
                'message' => $leave_save ? 'Leave Request Added successfully!' : 'Leave Request Failed to Add!'
            ]);
    }


    public function updateEscalate(Request $request)
    {
        DB::table('leaves')
            ->where('id', $request->id)
            ->update(['esculate_to' => 3, 'updated_at' => now()]);

        return response()->json(['message' => 'Escalated successfully!']);
    }

    public function send_not(Request $req)
    {

        // try {
        //     // Setup Google Client
        //     $client = new Client();
        //     $client->setAuthConfig(storage_path('app/firebase.json')); // Replace with your service account file path
        //     $client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);

        //     $fcm = new FirebaseCloudMessaging($client);

        //     // Create Notification
        //     $notification = new FcmNotification();
        //     $notification->setTitle('Test Notification');
        //     $notification->setBody('This is a test notification from your Laravel app.');

        //     // Create Message
        //     $message = new Message();
        //     $message->setToken($req->not_token);
        //     $message->setNotification($notification);

        //     // Create Send Message Request
        //     $sendMessageRequest = new SendMessageRequest();
        //     $sendMessageRequest->setMessage($message);

        //     // Send Message
        //    $res =  $fcm->projects_messages->send('projects/rootments-app', $sendMessageRequest); // Replace with your project ID

        //     return response()->json(['success' => true,'res'=>$res]);
        // } catch (\Exception $e) {
        //     return response()->json(['success' => false, 'error' => $e->getMessage()]);
        // }


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
