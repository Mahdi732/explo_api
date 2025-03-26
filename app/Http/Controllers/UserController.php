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

    public function index(){
        return User::all();
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|string|max:205|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = JWTAuth::fromUser($user);

        $response = [
            'token' => $token,
            'user' => $user
        ];

        return response($response, 200);
    }

    public function login(Request $request){
        $data = $request->validate([
            'email' => 'required|string|max:205',
            'password' => 'required|string|min:8'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user){
            return response()->json([
                'message' => 'UNauthorised'
            ], 401);
        }

        if (!Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'I…I’ve been thinking every day since coming here. How did things turn out this way? Ruined minds and bodies… People with no freedom left… People who have even lost themselves… What kind of person would want to go to war if they knew they were going to end up like this…? But there was something there, all along, pushing us right into hell. For most of us, that something is not our own free will. We’re forced to by others, or by our environment. That’s why the people who push themselves into hell see a different hell from the rest of us. They also see something beyond that hell. Maybe it’s hope. Maybe it’s yet another hell. I don’t know which it is. The only people who do know…are the ones who keep moving forward'
            ], 401);
        }

        try {
            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json([
                    'message' => 'Could not create token'
                ], 500);
            }
        } catch (JWTException $th) {
            return response()->json([
                'message' => 'I don’t know what are you doing here'
            ], 500);
        }

        $response = [
            'user' => $data,
            'token' => $token
        ];

        return response($response, 200);
    }
}


