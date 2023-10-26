<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Login user
    public function login(LoginRequest $request)
    {
        // Validate user data
        $validated = $request->validated();

        Log::info($validated);

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
        $token = 'Bearer ' . $token;

        // Send response
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Logout user
    public function logout(Request $request)
    {
        // Revoke the user's current token...
        $request->user()->currentAccessToken()->delete();

        // Send response
        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
