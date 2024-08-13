<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProyekController extends Controller
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

        $get = Proyek::all();
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
            'code' => 'required|unique:proyek',
            'name' => 'required',
            'description' => 'required',
            'customer' => 'required',
        ]);
    
        if ($validator->fails()) {
            return $this->format(400, false, null, $validator->errors());
        }
        
        $proyek = Proyek::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'customer' => $request->customer,
        ]);

        if($proyek) {
            return $this->format(200, true, $proyek, 'Create Proyek Successfuly');
        } else {
            return $this->format(400, false, null, 'Create Proyek Failed');
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

        $proyek = Proyek::find($id);
        if (!$proyek) {
            return $this->format(400, false, null, 'data not found');
        }
       
        if($proyek) {
            return $this->format(200, true, $proyek, 'Successfuly to get Proyek');
        } else {
            return $this->format(400, false, null, 'Failed to get Proyek');
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

        $proyek = Proyek::find($id);
        if (!$proyek) {
            return $this->format(400, false, null, 'data not found');
        }

        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|unique:proyek,code,' . $proyek->id,
            'name' => 'sometimes',
            'description' => 'sometimes',
            'customer' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return $this->format(400, false, null, $validator->errors());
        }

        $proyek->update([
            'code' => $request->code ? $request->code : $proyek->code,
            'name' => $request->name ? $request->name : $proyek->name,
            'description' => $request->description ? $request->description : $proyek->description,
            'customer' => $request->customer ? $request->customer : $proyek->customer,
        ]);

        return $this->format(200, true, $proyek, 'Update Proyek Successfully');
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

        $proyek = Proyek::find($id);
        if (!$proyek) {
            return $this->format(400, false, null, 'data not found');
        }

        $proyek->delete();
        return $this->format(200, true, null, 'Delete Proyek Successfully');
    }
}
