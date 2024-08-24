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
     * @return Subscription|null
     * @throws \Exception
     */
    public function subscribeToModule(Module $module, SubscriptionType $subscriptionType): ?Subscription
    {
        $this->validateSubscriptionType($subscriptionType);
        $this->validateModule($module);

        $price = $this->calculatePriceBasedOnType($module->price, $subscriptionType);
        $nextBillingDate = $this->calculateNextBillingDate($subscriptionType);

        // Create or update the subscription
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
     * Validate the subscription type.
     *
     * @param SubscriptionType $subscriptionType
     * @return void
     * @throws \Exception
     */
    protected function validateSubscriptionType(SubscriptionType $subscriptionType): void
    {
        if (!in_array($subscriptionType->value, SubscriptionType::values())) {
            throw new \Exception("Invalid subscription type: {$subscriptionType->value}");
        }
    }

    /**
     * Validate the module for subscription.
     *
     * @param Module $module
     * @return void
     * @throws \Exception
     */
    protected function validateModule(Module $module): void
    {
        if (!$module || !$module->exists) {
            throw new \Exception("Invalid module provided.");
        }
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
        $discountRates = $this->getDiscountRates();

        if (in_array($subscriptionType, [SubscriptionType::FREE, SubscriptionType::TRIAL])) {
            return 0;
        }

        $discountRate = $discountRates[$subscriptionType->value] ?? 0;
        return $basePrice * (1 - $discountRate);
    }

    /**
     * Get the discount rates for different subscription types.
     *
     * @return array
     */
    protected function getDiscountRates(): array
    {
        return [
            SubscriptionType::MONTHLY => 0,
            SubscriptionType::QUARTERLY => 0.05,
            SubscriptionType::BIANNUAL => 0.1,
            SubscriptionType::ANNUAL => 0.2,
        ];
    }

    /**
     * Calculate the next billing date based on the subscription type.
     *
     * @param SubscriptionType $subscriptionType
     * @return Carbon
     */
    protected function calculateNextBillingDate(SubscriptionType $subscriptionType): Carbon
    {
        return match ($subscriptionType) {
            SubscriptionType::TRIAL => Carbon::now()->addDays(14),
            SubscriptionType::FREE => Carbon::now()->addYears(100),
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