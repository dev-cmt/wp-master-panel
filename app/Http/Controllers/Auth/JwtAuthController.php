<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Check if api_token exists, else generate new
        $token = $user->api_token ?? JWTAuth::fromUser($user);
        $user->update([
            'api_token' => $user->api_token ?? $token
        ]);

        return response()->json([
            'status' => 200,
            'token' => $token,
            'user' => $user->only('name', 'email', 'phone')
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // expects password_confirmation field
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        $user->update([
            'api_token' => $token
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'User registered successfully',
            'user' => $user->only('name', 'email', 'phone'),
            'token' => $token
        ]);
    }
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['status' => true, 'message' => 'Logged out']);
    }

    public function refresh()
    {
        return response()->json(['status' => true, 'token' => JWTAuth::refresh(JWTAuth::getToken())]);
    }

}
