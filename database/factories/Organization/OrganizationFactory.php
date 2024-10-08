<?php

namespace Database\Factories\Organization;

use App\Enums\Admin\Organization\OrganizationCategory;
use App\Enums\Admin\Organization\OrganizationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => OrganizationCategory::CORPORATE,
            'type' => OrganizationType::CLIENT,
            'name' => fake()->name(),
            'description' => fake()->text(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone' => fake()->phoneNumber(),
            'email_verified_at' => now(),
            'address' => fake()->address(),
            'website' => fake()->url(),
            'logo' => fake()->imageUrl(),
            'verified' => true,
            'active' => true,
        ];
    }

    /**
     * Indicate that the organization is owner type.
     */
    public function owner(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => OrganizationType::OWNER,
            ];
        });
    }

    /**
     * Indicate that the organization is government category.
     */
    public function government(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'category' => OrganizationCategory::GOVERNMENT,
            ];
        });
    }

    /**
     * Indicate that the organization is CORPORATE category.
     */
    public function corporate(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'category' => OrganizationCategory::CORPORATE,
            ];
        });
    }

    /**
     * Indicate that the organization is NONPROFIT category.
     */
    public function nonprofit(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'category' => OrganizationCategory::NONPROFIT,
            ];
        });
    }

    /**
     * Indicate that the organization is verified.
     */
    public function verified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'verified' => true,
            ];
        });
    }

    /**
     * Indicate that the organization is unverified.
     */
    public function unVerified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'verified' => false,
            ];
        });
    }

    /**
     * Indicate that the organization is active.
     */
    public function active(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => true,
            ];
        });
    }

    /**
     * Indicate that the organization is inactive.
     */
    public function inActive(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => false,
            ];
        });
    }
}