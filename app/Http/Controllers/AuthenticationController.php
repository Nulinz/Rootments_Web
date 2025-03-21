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

        $user = User::where('emp_code', $emp_code)->where('status',1)->first();


        if ($user) {


            if (Auth::attempt(['emp_code' => $emp_code, 'password' => $password])) {

                $user = DB::table('users')
                    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->where('users.id', Auth::id())
                    ->select('roles.id as role_id','roles.role','roles.role_dept','users.store_id','users.id')
                    ->first();



                        $routes = [
                            1 => 'gm.dashboard',
                            2 => 'gm.dashboard',
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

            Auth::logout();
        }

        session()->flush();

        return redirect()->route('login')->with(['status' => 'success', 'message' => 'Successfully Logout']);
    }


}
