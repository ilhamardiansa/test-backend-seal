<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'profile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->format(400, false, null, $validator->errors());
        }

        $file = $request->file('profile');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('profile', $fileName, 'public');

        $relativePath = Storage::url($filePath);
        $fullUrl = url($relativePath);
        
        $user = User::create([
            'profile' => $fullUrl,
            'name' => $request->name,
            'email' => $request->email,
            'level' => 'employee',
            'password' => bcrypt($request->password),
        ]);
        
        $data = [
            'profile' => $fullUrl,
            'name' => $request->name,
            'email' => $request->email,
            'level' => 'employee',
        ];

        if($user) {
            return $this->format(200, true, $data, 'Register Successfuly');
        } else {
            return $this->format(400, false, null, 'Register Failed');
        }
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->format(400, false, null, $validator->errors());
        }

        $token = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $data = [
            'token' => $token
        ];

        if ($token){
            return $this->format(200, true, $data, 'Login Successfuly');
        } else {
            return $this->format(400, false, null, 'Failed to generate token');
        }
    }

    public function logout()
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            return $this->format(401, false, null, 'Unauthorized');
        }

        
        $invalidate = JWTAuth::invalidate($token);

        if($invalidate) {
            return $this->format(200, true, null, 'Logout Successfuly');
        } else {
            return $this->format(500, false, null, 'Logout failed');
        }
    }
}
