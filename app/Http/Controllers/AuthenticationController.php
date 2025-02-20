<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthenticationController extends Controller
{
        public function login(Request $request)
    {
        $request->validate([
            'emp_code' => 'required',
            'password' => 'required',
        ]);

        $emp_code = $request->emp_code;
        $password = $request->password;

        $user = User::where('emp_code', $emp_code)->first();


        if ($user) {
            // $alreadyLoggedIn = DB::table('attendance')
            //     ->where('user_id', $user->id)
            //     ->whereDate('c_on', now()->format('Y-m-d'))
            //     ->exists();

            if (Auth::attempt(['emp_code' => $emp_code, 'password' => $password])) {
                // if (!$alreadyLoggedIn) {
                //     DB::table('attendance')->insert([
                //         'user_id' => $user->id,
                //         'attend_status' => 'Present',
                //         'in_location' => 'Some Location',
                //         'in_time' => now()->format('H:i:s'),
                //         'c_on' => now()->format('Y-m-d')
                //     ]);
                // }

                $user = DB::table('users')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->where('users.id', Auth::id())
                    ->select('roles.id as role_id','roles.role','roles.role_dept')
                    ->first();

                    // dd($user);

                    $r_id = $user->role_id;

                    if($r_id == 3 || $r_id == 4 || $r_id == 5){
                            $route = 'hr.dashboard';
                    }elseif($r_id == 12){
                        $route = 'dashboard';
                    }elseif($r_id==11){
                        $route = 'cluster.dashboard';
                    }else{
                        $route = 'mydash.dashboard';
                    }

                return redirect()->route($route)->with([
                    'status' => 'success',
                    'message' => 'Welcome ' . Auth::user()->name
                ]);
            }

            return redirect()->route('login')->with(['status' => 'error', 'message' => 'Invalid Password']);
        }




        return redirect()->route('login')->with(['status' => 'error', 'message' => 'Invalid Emp Code']);
    }

    public function logout()
    {
        if (Auth::check()) {
            $user = Auth::user();

            // DB::table('attendance')
            //     ->where('user_id', $user->id)
            //     ->whereDate('in_time', now()->toDateString())
            //     ->update([
            //         'out_time' => now()->format('H:i:s'),
            //         'u_by'=>now()->format('Y-m-d')
            //     ]);

            Auth::logout();
        }

        session()->flush();

        return redirect()->route('login')->with(['status' => 'success', 'message' => 'Successfully Logout']);
    }


}
