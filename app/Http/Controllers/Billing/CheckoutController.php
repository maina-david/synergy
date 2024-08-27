<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\AddItemToCartRequest;
use App\Http\Requests\Billing\RemoveItemFromCartRequest;
use App\Services\Billing\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Add an item to the cart.
     *
     * @param AddItemToCartRequest $request
     * @return JsonResponse
     */
    public function addItemToCart(AddItemToCartRequest $request)
    {
        $data = $request->validated();

        if ($data['item_type'] == 'module') {
            $data['item_class'] = 'App\Models\Administration\Module';
        }

        if ($data['item_type'] == 'storage') {
            $data['item_class'] = 'App\Models\Organization\OrganizationStorageSpace';
        }

        $this->cartService->addItemToCart($data['item_class'], $data['item_id'], $data['quantity']);

        return redirect()->back()->with('success', 'Item added to cart successfully');
    }

    /**
     * Remove an item from the cart.
     *
     * @param RemoveItemFromCartRequest $request
     * @return JsonResponse
     */
    public function removeItemFromCart(RemoveItemFromCartRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($data['item_type'] == 'module') {
            $data['item_class'] = 'App\Models\Administration\Module';
        }

        if ($data['item_type'] == 'storage') {
            $data['item_class'] = 'App\Models\Organization\OrganizationStorageSpace';
        }

        $this->cartService->removeItemFromCart($data['item_class'], $data['item_id']);

        return response()->json(['success' => 'Item removed from cart successfully']);
    }

    /**
     * Retrieve and return the cart items in the required format.
     *
     * @return JsonResponse
     */
    public function getCartItems(): JsonResponse
    {
        $cartItems = $this->cartService->getFormattedCartItems();

        return response()->json($cartItems);
    }
}