<?php

namespace App\Http\Controllers\Setup;

use App\Enums\Billing\Subscription\SubscriptionType;
use App\Http\Controllers\Controller;
use App\Models\Administration\Module;
use App\Models\Administration\ModuleCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('AllProducts', [
            'moduleCategories' => ModuleCategory::with(['modules' => fn($query) => $query->active()])->get(),
        ]);
    }

    /**
     * Subscribe to module.
     */
    public function subscribe(Request $request, Module $module)
    {
        $organizationId = $request->user()->organization_id;

        $module->subscriptions()->updateOrCreate(
            [
                'organization_id' => $organizationId,
            ],
            [
                'subscription_type' => SubscriptionType::from(strtolower($module->subscription_type)),
                'price' => $module->price,
                'next_billing_date' => now()->addMonth(),
            ]
        );

        return redirect()->back()->with('success', "Subscribed to $module->name successfully!");
    }

    /**
     * Unsubscribe from a module.
     */
    public function unsubscribe(Module $module)
    {
        $module->subscriptions()->delete();

        return redirect()->back()->with('success', "Unsubscribed from $module->name successfully!");
    }
}