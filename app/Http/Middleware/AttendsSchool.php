<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\School;

class AttendsSchool
{
    /**
     * Check if auth user, profile param and user params attend the give school
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $school = $request->school instanceof \App\Models\School ? $request->school : School::find($request->school);

        $userIds = [$request->user()->id];

        $userIds = $this->addProfileToCheck($request, $userIds);
        $userIds = $this->addUserToCheck($request, $userIds);

        $atSchool = $school->users()->whereIn('users.id', $userIds)->get();
        
        $belongsToSchool = collect($userIds)->unique()->count() === $atSchool->count();

        $name = !$atSchool->firstwhere('id', $request->user()->id) ? 'Auth User' : 'Account';
        
        abort_if(!$belongsToSchool, 403, "{$name} does not belong to this school");

        return $next($request);
    }

    /**
     * Add profile request params to check 
     *
     * @param Request $request
     * @param array $userIds
     * @return array
     */
    protected function addProfileToCheck(Request $request, array $userIds) :array
    {
        if ($request->profile instanceof \App\Models\Profile) {
            array_push($userIds, $request->profile->user_id);
        }
        return $userIds;
    }

    /**
     * Add user request param to check
     *
     * @param Request $request
     * @param array $userIds
     * @return array
     */
    protected function addUserToCheck(Request $request, array $userIds) :array
    {
        if ($request->user instanceof \App\Models\User) {
            array_push($userIds, $request->user->id);
        }
        return $userIds;
    }
}
