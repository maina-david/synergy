<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organization_cart_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_cart_id')
                ->constrained('organization_carts', 'id')
                ->onDelete('cascade');
            $table->morphs('item');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_cart_items');
    }
};