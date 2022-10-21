<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Role;


class OverviewController extends Controller
{
    public function getCount(School $school) 
    {
        $accountsTotal = \DB::table('schools')
        ->where('schools.id', $school->id)
        ->join('school_user', 'school_user.school_id', 'schools.id')
        ->join('role_user', 'school_user.user_id', 'role_user.user_id')
        ->join('roles', 'roles.id', 'role_user.role_id')
        ->selectRaw('roles.nice_name, COUNT(school_user.user_id) as count')
        ->groupBy('roles.nice_name')
        ->get();

        return response()->json($accountsTotal); 
        
    }
}
