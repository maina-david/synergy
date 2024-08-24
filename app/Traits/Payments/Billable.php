<?php

namespace App\Traits\Payments;

use App\Models\Administration\Module;
use App\Models\Administration\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Enums\Billing\Subscription\SubscriptionType;

trait Billable
{
    /**
     * Subscribe an organization to a module with a specific subscription type.
     *
     * @param Module $module
     * @param SubscriptionType $subscriptionType
     * @return Subscription
     */
    public function subscribeToModule(Module $module, SubscriptionType $subscriptionType): Subscription
    {
        $price = $this->calculatePriceBasedOnType($module->price, $subscriptionType);

        $nextBillingDate = $this->calculateNextBillingDate($subscriptionType);

        return Subscription::updateOrCreate(
            [
                'module_id' => $module->id,
            ],
            [
                'subscription_type' => $subscriptionType->value,
                'price' => $price,
                'next_billing_date' => $nextBillingDate,
            ]
        );
    }

    /**
     * Calculate the price based on subscription type and apply discounts if applicable.
     *
     * @param float $basePrice
     * @param SubscriptionType $subscriptionType
     * @return float
     */
    protected function calculatePriceBasedOnType(float $basePrice, SubscriptionType $subscriptionType): float
    {
        $discountRates = [
            SubscriptionType::MONTHLY => 0,
            SubscriptionType::QUARTERLY => 0.05,
            SubscriptionType::BIANNUAL => 0.1,
            SubscriptionType::ANNUAL => 0.2
        ];

        if ($subscriptionType === SubscriptionType::FREE || $subscriptionType === SubscriptionType::TRIAL) {
            return 0;
        }

        return $basePrice * (1 - ($discountRates[$subscriptionType->value] ?? 0));
    }

    /**
     * Calculate the next billing date based on the subscription type.
     *
     * @param SubscriptionType $subscriptionType
     * @return Carbon
     */
    protected function calculateNextBillingDate(SubscriptionType $subscriptionType): Carbon
    {
        if ($subscriptionType === SubscriptionType::TRIAL) {
            return Carbon::now()->addDays(14);
        }

        if ($subscriptionType === SubscriptionType::FREE) {
            return Carbon::now()->addYears(100);
        }

        return match ($subscriptionType) {
            SubscriptionType::QUARTERLY => Carbon::now()->addMonths(3),
            SubscriptionType::BIANNUAL => Carbon::now()->addMonths(6),
            SubscriptionType::ANNUAL => Carbon::now()->addYear(),
            default => Carbon::now()->addMonth(),
        };
    }

    /**
     * Calculate the total billing amount for the organization based on subscribed modules.
     *
     * @return float
     */
    public function calculateBillingAmount(): float
    {
        return $this->subscriptionsDueForBilling()
            ->sum(fn($subscription) => $this->calculatePriceBasedOnType(
                $subscription->module->price,
                SubscriptionType::from($subscription->subscription_type)
            ));
    }

    /**
     * Get subscriptions due for billing.
     *
     * @return Collection
     */
    protected function subscriptionsDueForBilling(): Collection
    {
        return $this->subscriptions()
            ->where('next_billing_date', '<=', Carbon::now())
            ->get();
    }
}