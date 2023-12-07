<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Login user
    public function login(LoginRequest $request)
    {
        // Validate user data
        $validated = $request->validated();

        // Check email
        $user = User::where('email', $validated['email'])->first();

        // Check password
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
                'password' => ['The provided credentials are incorrect'],
            ]);
        }

        // Create token
        $token = $user->createToken('user-token')->plainTextToken;

        // Set cookie
        $cookie = cookie('jwt', $token, 60 * 24); // 1 day

        // Send response
        return response()->json([
            'message' => 'Login succeeded.'
        ])->withCookie($cookie);
    }

    // Get user data
    public function user()
    {
        return Auth::user();
    }

    // Logout user
    public function logout()
    {
        $cookie =  Cookie::forget('jwt');

        // Send response
        return response()->json([
            'message' => 'Logged out'
        ])->withCookie($cookie);
    }
}
