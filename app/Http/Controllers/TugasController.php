<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
    
        if (!$user) {
            return $this->format(401, false, null, 'Unauthorized');
        }
    
        if ($user->level == 'employee') {
            $get = Tugas::where('user_id', $user->id)->get();
        } else {
            $get = Tugas::all();
        }
    
        if ($get->isEmpty()) {
            return $this->format(404, false, null, 'No Data Found');
        }
    
        return $this->format(200, true, $get, 'Success to get Data');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
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
            'proyek_id' => 'required|exists:proyek,id',
            'name' => 'required',
            'description' => 'required',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:waiting,processing,done',
        ]);
    
        if ($validator->fails()) {
            return $this->format(400, false, null, $validator->errors());
        }
        
        $tugas = Tugas::create([
            'proyek_id' => $request->proyek_id,
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'status' => $request->status,
        ]);

        if($tugas) {
            return $this->format(200, true, $tugas, 'Create Tugas Successfuly');
        } else {
            return $this->format(400, false, null, 'Create Tugas Failed');
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

        $tugas = Tugas::find($id);
        if (!$tugas) {
            return $this->format(400, false, null, 'data not found');
        }
       
        if($tugas) {
            return $this->format(200, true, $tugas, 'Successfuly to get Tugas');
        } else {
            return $this->format(400, false, null, 'Failed to get Tugas');
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

        $tugas = Tugas::find($id);
        if (!$tugas) {
            return $this->format(400, false, null, 'data not found');
        }

        $validator = Validator::make($request->all(), [
            'proyek_id' => 'sometimes|exists:proyek,id',
            'name' => 'sometimes',
            'description' => 'sometimes',
            'user_id' => 'sometimes|exists:users,id',
            'status' => 'sometimes|in:waiting,processing,done',
        ]);

        if ($validator->fails()) {
            return $this->format(400, false, null, $validator->errors());
        }

        $tugas->update([
            'proyek_id' => $request->proyek_id ? $request->proyek_id : $tugas->proyek_id,
            'name' => $request->name ? $request->name : $tugas->name,
            'description' => $request->description ? $request->description : $tugas->description,
            'user_id' => $request->user_id ? $request->user_id : $tugas->user_id,
            'status' => $request->status ? $request->status : $tugas->status,
        ]);

        return $this->format(200, true, $tugas, 'Update Pengguna Tugas');
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

        $tugas = Tugas::find($id);
        if (!$tugas) {
            return $this->format(400, false, null, 'data not found');
        }

        $tugas->delete();
        return $this->format(200, true, null, 'Delete Tugas Successfully');
    }
}
