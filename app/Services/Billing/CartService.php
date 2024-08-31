<?php

namespace App\Services\Billing;

use App\Enums\Billing\Cart\AllowedCartItemTypes;
use App\Enums\Billing\Subscription\SubscriptionType;
use App\Models\Administration\Module;
use App\Models\Organization\OrganizationCart;
use App\Models\Organization\OrganizationStorageSpace;
use Illuminate\Database\Eloquent\Model;
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
     * @param string $frequency
     * @return void
     */
    public function addItemToCart(string $itemType, string $itemId, int $quantity, string $frequency): void
    {
        DB::transaction(function () use ($itemType, $itemId, $quantity, $frequency) {
            $organizationId = Auth::user()->organization_id;

            $organizationCart = OrganizationCart::firstOrCreate([
                'organization_id' => $organizationId,
                'user_id' => Auth::id(),
            ]);

            $item = $this->resolveItem($itemType, $itemId);

            if ($item && !$organizationCart->hasItem($item)) {
                $organizationCart->addItem($item, $quantity, $frequency);
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

            $organizationCart = OrganizationCart::where('organization_id', $organizationId)
                ->where('user_id', Auth::id())
                ->first();

            if ($organizationCart) {
                $item = $this->resolveItem($itemType, $itemId);
                if ($item && $organizationCart->hasItem($item)) {
                    $organizationCart->removeItem($item);
                }
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
        return match ($itemType) {
            'module' => Module::find($itemId),
            'storage' => OrganizationStorageSpace::find($itemId),
            default => null,
        };
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

        if (!$organizationCart) {
            return [];
        }

        return $organizationCart->cartItems->map(function ($cartItem) {
            $item = $cartItem->item;
            return [
                'id' => $item->id,
                'name' => $this->getItemName($item),
                'quantity' => $cartItem->quantity,
                'price' => $this->getItemPrice($item, $cartItem->frequency),
                'type' => $this->getItemType($cartItem->item_type),
                'frequency' => $cartItem->frequency
            ];
        })->toArray();
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
     * @param Model $item
     * @param SubscriptionType $frequency
     * @return float
     */
    private function getItemPrice(Model $item, SubscriptionType $frequency): float
    {
        return match ($frequency) {
            SubscriptionType::ANNUAL => $item->price * 10,
            default => $item->price ?? 0.0
        };
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
            Module::class => AllowedCartItemTypes::MODULE,
            OrganizationStorageSpace::class => AllowedCartItemTypes::STORAGE,
            default => AllowedCartItemTypes::MODULE,
        };
    }
}