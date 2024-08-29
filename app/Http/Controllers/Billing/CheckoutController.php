<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\AddItemToCartRequest;
use App\Http\Requests\Billing\RemoveItemFromCartRequest;
use App\Services\Billing\CartService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

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
     * @return RedirectResponse
     */
    public function addItemToCart(AddItemToCartRequest $request)
    {
        $data = $request->validated();

        $type = ucfirst($data['item_type']);

        $this->cartService->addItemToCart($data['item_type'], $data['item_id'], $data['quantity'], $data['frequency']);

        return response()->json(['success' => "$type added to cart successfully!"]);
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

        $this->cartService->removeItemFromCart($data['item_type'], $data['item_id']);

        return response()->json(['success' => 'Item removed from cart successfully!']);
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

    /**
     * The `checkout` function in PHP renders the 'Billing/Checkout' template with formatted cart items.
     *
     * @return Response
     */
    public function checkout(): Response
    {
        return Inertia::render('Billing/Checkout', [
            'cartItems' => $this->cartService->getFormattedCartItems(),
        ]);
    }
}