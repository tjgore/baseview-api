<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function all()
    {
        return response()->json(Role::schoolRoles()->get());
    }
}
