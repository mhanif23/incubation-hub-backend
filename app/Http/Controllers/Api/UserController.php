<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Registration for applicants
    public function register(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Validation Error', [], $validator->errors());
        }

        $applicant = Applicant::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->api_response_success('User registered successfully', ['user' => $applicant], [], 201);
    }

    // Sign-in
    public function signIn(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->api_response_validator('Validation Error', [], $validator->errors());
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->api_response_error('The provided credentials are incorrect', [], [], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->api_response_success('User signed in successfully', ['access_token' => $token, 'token_type' => 'Bearer']);
    }

    // Sign-out
    public function signOut(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->api_response_success('Successfully logged out');
    }

    // Forgot password
    public function forgotPassword(Request $request)
    {
        $validator = $request->validate(['email' => 'required|email']);

        if ($validator->fails()) {
            return $this->api_response_validator('Validation Error', [], $validator->errors());
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? $this->api_response_success('Password reset link sent')
                    : $this->api_response_error('Unable to send password reset link', [], [], 500);
    }
}
