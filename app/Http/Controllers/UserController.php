<?php

namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
Use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function registerUser(Request $req){

        $req->validate([
            "name"=> "required",
            "email"=> "email|required|unique:users",
            "password"=> "required"
        ]);
        
        $name = $req->name;
        $email = $req->email;
        $password = $req->password;
        
        $user =  new User();
    
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();
        
        $user->assignRole('employee');
        

        $data = [
            'message' => 'User Registerd Successfully',
            'code' => 200,
            'data' => $user,  
        ];

        return response()->json($data);
    }

    public function login(Request $req){

        $email = $req->email;
        $password = $req->password;
        $user = User::where(['email' => $email])->first();

        if(!$user)
        {
            return response([
                "code" => "404",
                "message"=> "User not found"
            ]);
        }

        if(!Hash::check($password, $user->password))
        {
            return response([
                "message"=>"Incorrect Password"
            ]);
        }
        $roles = $user->getRoleNames();
        foreach ($user->tokens as $tKey => $token) {
            $token->delete();
        }
        
        unset($user['tokens']);
        unset($user['roles']);

        $user['role'] = $roles[0];
        $token = $user->createToken($user->name)->plainTextToken;

        $data = [
            'message' => 'User logged in Successfully',
            'code' => 200,
            'data' => $user,
            'token' => $token
        ];

        return response()->json($data);
    }

    public function logout(Request $request){

        $request->user()->tokens()->delete();

        return response([
            "code" => 200,
            "message" => "User Logged out successfully"
        ]);
    }

    public function userDetails(Request $req){
        
        $email = $req->email;
        $user = User::where(['email' => $email])->with(['topics'])->first();

        if(!$user)
        {
            return response([
                "code" => "404",
                "message"=> "User not found"
            ]);
        }

        $data = [
            'message' => 'Success',
            'code' => 200,
            'data' => $user,
        ];

        return response()->json($data);
    }

    public function editDetials(Request $req){

        $req->validate([
            "id" => "required"
        ]);
        
        $id = $req->id;
        $name = $req->name;
        $email = $req->email;

        $user = User::find($id);

        if (!$user){
            return response([
                "code" => 404,
                "message" => "user not found"
            ]);
        }

        $user->name = $name;
        $user->email = $email;
        $user->save();
        
        return response([
            "code" => 200,
           "message" => "User Details Edited Successfully",
           "user" => $user 
        ]);
    }
}
