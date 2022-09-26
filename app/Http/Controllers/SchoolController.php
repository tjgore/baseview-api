<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolRequest;
use App\Models\School;
use Illuminate\Support\Facades\Gate;


class SchoolController extends Controller
{
    public function all()
    {
        return response()->json(request()->user()->schools);
    }

    public function find(School $school)
    {
        Gate::authorize('school', $school);
        return response()->json($school);
    }

    public function create(SchoolRequest $request)
    {   
        Gate::authorize('create-school');
        
        $school = new School;
        $user = $request->user();

        $school = $this->setSchool($school, $request);

        $school->users()->attach($user->id);

        return $this->ok(201);
    }

    public function edit(School $school, SchoolRequest $request)
    {
        Gate::authorize('school', $school);

        $this->setSchool($school, $request);

        return $this->ok();
    }

    protected function setSchool(School $school, SchoolRequest $request)
    {
        $school->name = $request->name;
        $school->address = $request->address;
        $school->email = $request->email;
        $school->phone = $request->phone;
        $school->website = $request->website;
        $school->principal = $request->principal;
        $school->vice_principal = $request->vice_principal;
        $school->about = $request->about;
        $school->slogan = $request->slogan;

        $school->save();

        return $school;
    }

    public function delete(School $school)
    {
        $school->delete();
        $this->ok();
    }
}
