<?php

namespace App\Http\Middleware;

use App\Enums\Billing\Payments\SupportedCurrency;
use App\Models\Administration\Module;
use App\Models\Administration\ModuleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Worksome\Exchange\Facades\Exchange;

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
        // $exchangeRates = Exchange::rates('USD', ['GBP', 'EUR', 'KES']);
        $baseCurrencies = ['USD' => 1, 'KES' => 130];
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'orgCurrency' => Auth::user()?->organization->preferred_currency,
            'exchangeRates' => $baseCurrencies,
            'appName' => config('app.name'),
            'flash' => [
                'status' => fn() => $request->session()->get('status'),
                'error' => fn() => $request->session()->get('error'),
                'success' => fn() => $request->session()->get('success'),
            ],
        ];
    }
}