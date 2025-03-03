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
                    ->select('roles.id as role_id','roles.role','roles.role_dept','users.store_id','users.id')
                    ->first();

                    // dd($user);

                    // $r_id = $user->role_id;
                    if($user->role_id==13){

                        $asm_count = DB::table('asm_store')->where('store_id',$user->store_id)->where('emp_id',$user->id)->count();

                        $asm_route = ($asm_count > 0) ? 'dashboard' : 'mydash.dashboard';

                        session(['role_id' => 12]);

                        if (session('role_id')) {
                            // Update the role_id of the authenticated user
                            $user = Auth::user();
                            $user->role_id = session('role_id');  // Update role_id from the session
                            Auth::setUser($user);  // Re-set the user to reflect the new role_id
                        }

                    }




                    $routes = [
                        3 => 'hr.dashboard',
                        4 => 'hr.dashboard',
                        5 => 'hr.dashboard',
                        7 => 'fin.index',
                        10 => 'area.dashboard',
                        11 => 'cluster.dashboard',
                        12 => 'dashboard',
                        30 => 'maintain.index',
                        37 => 'warehouse.index',
                        41 => 'purchase.index',
                    ];

                    $route = $routes[$user->role_id] ?? 'mydash.dashboard';


                return redirect()->route($route)->with([
                    'status' => 'success',
                    'message' => 'Welcome ' . Auth::user()->name,

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
