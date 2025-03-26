<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use PhpParser\Node\Stmt\TryCatch;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function index()
    {
        return response()->json(User::paginate(10));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|string|max:205|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:205',
            'password' => 'required|string|min:8'
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => Auth::user(),
            'token' => $token
        ], 200);
    }
}


