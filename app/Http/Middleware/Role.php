<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role as RoleModel;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        // Internal admin has full access.
        array_push($roles, RoleModel::INTERNAL_ADMIN);

        $hasValidRole = $request->user()->roles()->whereIn('id', $roles)->exists();

        abort_if(!$hasValidRole, 403, 'Invalid user role');

        return $next($request);
    }
}
