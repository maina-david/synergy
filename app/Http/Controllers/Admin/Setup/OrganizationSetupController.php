<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Enums\Admin\Organization\OrganizationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setup\StoreOrganizationRequest;
use App\Models\Administration\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrganizationSetupController extends Controller
{
    public function showSetupOrganization(Request $request)
    {
        if ($request->user()->belongsToOrganization()) {
            return redirect()->intended(route('products', absolute: false));
        }
        return Inertia::render('Setup/OrganizationSetup');
    }

    /**
     * Creates a new organization
     * 
     * @param StoreOrganizationRequest $request
     * @return RedirectResponse 
     */
    public function createOrganization(StoreOrganizationRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validatedData['logo'] = $logoPath;
        }

        $validatedData['type'] = OrganizationType::CLIENT;

        try {
            $organization = Organization::create($validatedData);
            /**
             * @var App/Models/User $user
             */
            $user = Auth::user();
            $user->organization_id = $organization->id;
            $user->save();
            
            return redirect()->route('products')
                ->with('success', 'Organization created successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create organization: ' . $e->getMessage());
        }
    }
}