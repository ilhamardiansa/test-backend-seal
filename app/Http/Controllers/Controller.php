<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function format($code,$status,$data,$message) {
        return response()->json([
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
