<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $cocde = $request->input('code');

        $data = [
            'user_id' => 1,
            'session_key' => 'a'.$cocde
        ];
        return response()->json($data);
    }
}
