<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Login method to authenticate user and return token.
     */
   public function login(Request $request)
{
    $request->validate([
        'emp_code' => 'required',
        'password' => 'required',
        // 'device_token' => 'required',
    ]);

    //  $user = User::with('role')->where('emp_code', $request->emp_code)->first();

    $user = User::where('emp_code', $request->emp_code)->first();

    if (!$user) {
        return response()->json(['error' => 'User not registered.'], 404);
    }

    if (!Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }


    $user->device_token = $request->device_token;
    $user->save();

     $role = DB::table('roles')->where('id',$user->role_id)->select('role')->first();

    $token = $user->createToken('token')->plainTextToken;

     $profileImageUrl = $user->profile_image && file_exists($user->profile_image) ? url($user->profile_image) : null;

   return response()->json([
    'status' => 'success',
    'data' => [
        'id' => $user->id,
        'name' => $user->name,
        'emp_code' => $user->emp_code,
        'role' => $role->role ?? null,
        'role_id' => $user->role_id ?? null,
        'store_id' => $user->store_id ?? null,
        'profile_image_url' => $profileImageUrl,
        'token' => $token,
        ],
    ]);


}


    /**
     * Logout method to invalidate user session.
     */
    public function logout(Request $request)
    {
        $user = User::find(Auth::id()); // Find the authenticated user by ID

    //   /  dd($user);

        if ($user) {
            $user->device_token = null; // Set device_token to null
            $user->save(); // Save the updated user


            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out.',
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['sometimes', 'nullable', 'min:6'],
        ]);

        $user_id = $request->input('user_id');
        $password = $request->password;

        $user = User::where('id', $user_id)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized Profile'], 404);
        }

        if ($password) {
            if (Hash::check($password, $user->password)) {
                return response()->json(['status' => 'error', 'message' => 'You Entered Old Password'], 400);
            }

            $user->password = Hash::make($password);
            $user->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Password Updated Successfully'], 200);
    }


    public function popup(Request $request)
    {

        return response()->json(['version' => '1.0.1'], 200);
    }

}
