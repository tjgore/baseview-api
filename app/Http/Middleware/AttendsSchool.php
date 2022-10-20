<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AttendsSchool
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $belongsToSchool = $request->user()
            ->schools()
            ->where('school_id', $request->school->id)
            ->exists();

        abort_if(!$belongsToSchool, 403, 'User does not belong to this school');

        return $next($request);
    }
}
