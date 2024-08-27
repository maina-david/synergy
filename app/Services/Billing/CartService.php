<?php

namespace App\Services\Billing;

use App\Enums\Billing\Cart\AllowedCartItemTypes;
use App\Models\Administration\Module;
use App\Models\Organization\OrganizationCart;
use App\Models\Organization\OrganizationStorageSpace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Add items to the cart.
     *
     * @param string $itemType
     * @param string $itemId
     * @param int $quantity
     * @return void
     */
    public function addItemToCart(string $itemType, string $itemId, int $quantity): void
    {
        DB::transaction(function () use ($itemType, $itemId, $quantity) {
            $organizationId = Auth::user()->organization_id;

            $organizationCart = OrganizationCart::firstOrCreate([
                'organization_id' => $organizationId,
                'user_id' => Auth::id(),
            ]);

            $item = $this->resolveItem($itemType, $itemId);

            if ($item) {
                if ($organizationCart->hasItem($item)) {
                    return;
                } else {
                    $organizationCart->addItem($item, $quantity);
                }
            }
        });
    }

    /**
     * Remove an item from the cart.
     *
     * @param string $itemType
     * @param string $itemId
     * @return void
     */
    public function removeItemFromCart(string $itemType, string $itemId): void
    {
        DB::transaction(function () use ($itemType, $itemId) {
            $organizationId = Auth::user()->organization_id;

            // Retrieve the organization cart
            $organizationCart = OrganizationCart::where('organization_id', $organizationId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Determine the item type and retrieve the corresponding model
            $item = $this->resolveItem($itemType, $itemId);

            if ($item && $organizationCart->hasItem($item)) {
                $organizationCart->removeItem($item);
            }
        });
    }

    /**
     * Resolve item type to the corresponding model.
     *
     * @param string $itemType
     * @param string $itemId
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function resolveItem(string $itemType, string $itemId)
    {
        $modelClass = match ($itemType) {
            'App\Models\Administration\Module' => Module::class,
            'App\Models\Organization\OrganizationStorageSpace' => OrganizationStorageSpace::class,
            default => null,
        };

        return $modelClass ? $modelClass::find($itemId) : null;
    }

    /**
     * Retrieve and format the cart items.
     *
     * @return array
     */
    public function getFormattedCartItems(): array
    {
        $organizationId = Auth::user()->organization_id;

        $organizationCart = OrganizationCart::where('organization_id', $organizationId)
            ->where('user_id', Auth::id())
            ->first();

        // If no cart found, return an empty array
        if (!$organizationCart) {
            return [];
        }

        $formattedItems = $organizationCart->cartItems->map(function ($cartItem) {
            $item = $this->resolveItem($cartItem->item_type, $cartItem->item_id);
            return [
                'id' => $item->id,
                'name' => $this->getItemName($item),
                'quantity' => $cartItem->quantity,
                'price' => $this->getItemPrice($item),
                'type' => $this->getItemType($cartItem->item_type),
            ];
        })->toArray();

        return $formattedItems;
    }

    /**
     * Resolve the item's name.
     *
     * @param mixed $item
     * @return string
     */
    private function getItemName($item): string
    {
        return $item->name ?? 'Unknown';
    }

    /**
     * Resolve the item's price.
     *
     * @param mixed $item
     * @return float
     */
    private function getItemPrice($item): float
    {
        return $item->price ?? 0.0;
    }

    /**
     * Get the item type as an AllowedCartItemTypes enum.
     *
     * @param string $itemType
     * @return AllowedCartItemTypes
     */
    private function getItemType(string $itemType): AllowedCartItemTypes
    {
        return match ($itemType) {
            'App\Models\Administration\Module' => AllowedCartItemTypes::MODULE,
            'App\Models\Organization\OrganizationStorageSpace' => AllowedCartItemTypes::STORAGE,
            default => AllowedCartItemTypes::MODULE,
        };
    }
}