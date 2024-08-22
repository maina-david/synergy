<?php

namespace App\Http\Middleware;

use App\Models\Administration\Module;
use App\Models\Administration\ModuleCategory;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'moduleCategories' => ModuleCategory::with('modules')->get(),
            'modules' => Module::active()->get(),
            'appName' => config('app.name'),
            'flash' => [
                'status' => fn() => $request->session()->get('status'),
                'error' => fn() => $request->session()->get('error'),
                'success' => fn() => $request->session()->get('success'),
            ],
        ];
    }
}