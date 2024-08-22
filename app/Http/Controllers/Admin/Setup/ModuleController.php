<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Enums\Admin\Subscription\SubscriptionType;
use App\Http\Controllers\Controller;
use App\Models\Administration\Module;
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
        return Inertia::render('AllProducts');
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

        return redirect()->back()->with('message', "Subscribed to $module->name successfully!");
    }

    /**
     * Unsubscribe from a module.
     */
    public function unsubscribe(Request $request, Module $module)
    {
        $organizationId = $request->user()->organization_id;

        $module->subscriptions()->where('organization_id', $organizationId)->delete();

        return redirect()->back()->with('message', "Unsubscribed from $module->name successfully!");
    }
}