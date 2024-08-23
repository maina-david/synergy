<?php

namespace App\Http\Middleware\Organizations;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $organization = $request->user()->organization;

        if ($organization && !$organization->verified) {
            # code...
        }

        return $next($request);
    }
}