<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class UserController extends Controller
{
    public function find(Request $request)
    {   
        $user = $request->user();
        
        return response()->json([
            'user' => $user,
            'roles' => $user->roles,
            'rolesArray' => [
                'ids' => $user->roles()->pluck('id'),
                'names' => $user->roles()->pluck('name'),
                'nice_names' => $user->roles()->pluck('nice_name'),
            ],
            'schools' => $user->schools,
        ]);
    }
}
