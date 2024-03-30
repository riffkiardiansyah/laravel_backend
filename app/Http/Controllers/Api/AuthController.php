<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\support\Facades\validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'jenis_kelamin' => 'required',
            'password' => 'required',
            'alamat' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'jenis_kelamin' => $request->jenis_kelamin,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
    ]);

        if($user){
            return response()->json([
                'status' => true,
                'message' => 'User berhasil registrasi',
                'user' => $user
            ],201);

            return response()->json([
                'status' => false,
            ],409);
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username'=>'required',
            'password'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('username', 'password');

        if(!$token = auth()->guard('api')->attempt($credentials)){
            return response()->json([
                'success' => true,
                'message' => 'username atau password anda salah'
            ],401);
        }
        return response()->json([
            'status'=> true,
            'user' => auth()->guard('api')->user(),
            'token' => $token
            ],200);

        }

        public function logout(){
            $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

            if($removeToken){
                return response()->json([
                    'status' => true,
                    'message' => 'User Berhasil Logout'
                ],200);
            }
        }
    }

