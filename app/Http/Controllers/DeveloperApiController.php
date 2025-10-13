<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class DeveloperApiController extends Controller
{
    public function index()
    {
        return view('backend.developer-api.index');
    }

    public function generateToken(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return back()->with('error', 'User not authenticated.');
        }

        // Generate a new JWT token for this user
        $newToken = JWTAuth::fromUser($user);

        // Save the token in the database if you store it
        $user->update([
            'api_token' => $newToken
        ]);

        return back()->with('success', 'New API token generated!');
    }
}
