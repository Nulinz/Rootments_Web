<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class asm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();

        // // if($user->role_id==13 && !session()->has('role_updated')){
        // if($user->role_id==13){

        //     $asm_count = DB::table('asm_store')->where('store_id',$user->store_id)->where('emp_id',$user->id)->count();


        //     // session(['role_id' => 12]);
        //     session(['role_updated' => true]);

        //     // if (session('role_id')&&($asm_count>0)) {
        //         // Update the role_id of the authenticated user
        //         $user = Auth::user();
        //         $user->role_id = 12;  // Update role_id from the session
        //         Auth::setUser($user);  // Re-set the user to reflect the new role_id
        //     // }


        // }

        //   dd($user->role_id, session('role_id'), Auth::user());



        // if (Auth::check() && session()->has('role_id')) {
        //     $user = Auth::user();
        //     $user->role_id = session('role_id');  // Update role_id from session
        //     Auth::setUser($user);  // Update the Auth object with the new role_id
        // }


        return $next($request);
    }
}
