<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    public function find(Request $request)
    {   
        $user = $request->user();
        
        return response()->json([
            'user' => $user,
            'roles' => $user->roles
        ]);
    }
}
