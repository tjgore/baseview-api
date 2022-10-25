<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolRequest;
use App\Models\School;
use Illuminate\Http\JsonResponse;


class SchoolController extends Controller
{

    /**
     * Get all the school the auth user belongs to.
     *
     * @return JsonResponse
     */
    public function all() :JsonResponse
    {
        return response()->json(request()->user()->schools);
    }

    /**
     * Find school by id
     *
     * @param School $school
     * @return JsonResponse
     */
    public function find(School $school) :JsonResponse
    {
        return response()->json($school);
    }

    /**
     * Create a new school
     *
     * @param SchoolRequest $request
     * @return JsonResponse
     */
    public function create(SchoolRequest $request) :JsonResponse
    {           
        $school = new School;
        $user = $request->user();

        $school = $this->setSchool($school, $request);

        $school->users()->attach($user->id);

        return $this->ok(201);
    }

    /**
     * Update a school
     *
     * @param School $school
     * @param SchoolRequest $request
     * @return JsonResponse
     */
    public function update(School $school, SchoolRequest $request) :JsonResponse
    {
        $this->setSchool($school, $request);

        return $this->ok();
    }

    /**
     * Set school data and save
     *
     * @param School $school
     * @param SchoolRequest $request
     * @return School
     */
    protected function setSchool(School $school, SchoolRequest $request) :School
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

    /**
     * Delete school by id
     *
     * @param School $school
     * @return JsonResponse
     */
    public function delete(School $school) :JsonResponse
    {
        $school->delete();
        return $this->ok();
    }
}
