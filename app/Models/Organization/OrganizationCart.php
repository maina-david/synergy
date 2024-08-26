<?php

namespace App\Models\Organization;

use App\Models\Administration\Organization;
use App\Models\User;
use App\Traits\BelongsToOrganization;
use App\Traits\Users\AssociatedToUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationCart extends Model
{
    use HasUuids, BelongsToOrganization, AssociatedToUser;

    protected $fillable = [
        'organization_id',
        'user_id',
    ];

    /**
     * Get the organization that owns the OrganizationCart
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user that owns the OrganizationCart
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the cart items for the OrganizationCart
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(OrganizationCartItem::class);
    }

    /**
     * Add an item to the cart.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     * @param int $quantity
     * @return void
     */
    public function addItem(Model $item, int $quantity = 1)
    {
        $this->items()->updateOrCreate(
            [
                'item_type' => get_class($item),
                'item_id' => $item->id,
            ],
            ['quantity' => $quantity]
        );
    }

    /**
     * Remove an item from the cart.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     * @return void
     */
    public function removeItem(Model $item)
    {
        $this->items()->where('item_type', get_class($item))
            ->where('item_id', $item->id)
            ->delete();
    }

    /**
     * Check if an item is in the cart.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     * @return bool
     */
    public function hasItem(Model $item)
    {
        return $this->items()->where('item_type', get_class($item))
            ->where('item_id', $item->id)
            ->exists();
    }

    /**
     * Get the total number of items in the cart.
     *
     * @return int
     */
    public function getTotalItems()
    {
        return $this->items()->sum('quantity');
    }
}