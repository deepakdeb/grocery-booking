<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $role = Role::where('name', 'user')->firstOrFail();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role->id,
        ]);

        $user->load('role');
        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user,
            'token' => $token,
            'token_type' => 'bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! $token = auth('api')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $user = auth('api')->user()->load('role');

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logout successful.']);
    }

    public function refresh()
    {
        return response()->json([
            'token' => auth('api')->refresh(),
            'token_type' => 'bearer',
            'user' => auth('api')->user(),
        ]);
    }
}
