<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
   public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

    
        if (Auth::attempt($credentials)) {
            // if (Auth::check()) {
            //     $user_id = Auth::id();
            //     $token = $user->createToken('authToken')->plainTextToken;
 $user = Auth::user();
                return response()->json(['user' => $user], 200);
            // }
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }
}
