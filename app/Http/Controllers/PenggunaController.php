<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            return $this->format(401, false, null, 'Unauthorized');
        }

        $get = User::all();
        if ($get){
            return $this->format(200, true, $get, 'Success to get Data');
        } else {
            return $this->format(400, false, null, 'Server Error');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            return $this->format(401, false, null, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'profile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'level' => 'required',
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
            'level' => $request->level,
            'password' => bcrypt($request->password),
        ]);
        
        $data = [
            'profile' => $fullUrl,
            'name' => $request->name,
            'email' => $request->email,
            'level' => $request->level,
        ];

        if($user) {
            return $this->format(200, true, $data, 'Create Pengguna Successfuly');
        } else {
            return $this->format(400, false, null, 'Create Pengguna Failed');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            return $this->format(401, false, null, 'Unauthorized');
        }

        $user = User::find($id);
        if (!$user) {
            return $this->format(400, false, null, 'data not found');
        }
       
        if($user) {
            return $this->format(200, true, $user, 'Successfuly to get Pengguna');
        } else {
            return $this->format(400, false, null, 'Failed to get Pengguna');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            return $this->format(401, false, null, 'Unauthorized');
        }

        $user = User::find($id);
        if (!$user) {
            return $this->format(400, false, null, 'data not found');
        }

        $validator = Validator::make($request->all(), [
            'profile' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'sometimes|string|max:255|unique:users,name,' . $user->id,
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'level' => 'sometimes|string|max:255',
            'password' => 'sometimes|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->format(400, false, null, $validator->errors());
        }

        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('profile', $fileName, 'public');
            $fullUrl = url(Storage::url($filePath));
        } else {
            $fullUrl = $user->profile;
        }

        $user->update([
            'profile' => $fullUrl,
            'name' => $request->name ? $request->name : $user->name,
            'email' => $request->email ? $request->email : $user->email,
            'level' => $request->level ? $request->level : $user->level,
            'password' => bcrypt($request->password) ? $request->password : $user->password,
        ]);

        return $this->format(200, true, $user, 'Update Pengguna Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            return $this->format(401, false, null, 'Unauthorized');
        }

        $user = User::find($id);
        if (!$user) {
            return $this->format(400, false, null, 'data not found');
        }

        $user->delete();
        return $this->format(200, true, null, 'Delete Pengguna Successfully');
    }
}
