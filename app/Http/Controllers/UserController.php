<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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

        $response = [
            'user' => $user
        ];

        return response($response, 200);
    }

    public function login(Request $request){
        $data = $request->validate([
            'email' => 'required|string|max:205',
            'Password' => 'required|string|min:8'
        ]);

        $user = User::Where('email', $data['email'])->first();

        if(!$user || Hash::check($data['password'], $user->password)){
            return response([
                'message' => 'I…I’ve been thinking every day since coming here. How did things turn out this way? Ruined minds and bodies… People with no freedom left… People who have even lost themselves… What kind of person would want to go to war if they knew they were going to end up like this…? But there was something there, all along, pushing us right into hell. For most of us, that something is not our own free will. We’re forced to by others, or by our environment. That’s why the people who push themselves into hell see a different hell from the rest of us. They also see something beyond that hell. Maybe it’s hope. Maybe it’s yet another hell. I don’t know which it is. The only people who do know…are the ones who keep moving forward'], 401);
        }

        $response = [
            'user' => $user,
            'message' => 'rak a kt5ra'
        ];

        return response($response, 200);
    }
}


