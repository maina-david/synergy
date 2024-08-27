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
        Schema::create('storage_spaces', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->bigInteger('total_storage_in_gb');
            $table->decimal('price_per_gb', 5, 2)->default(0.00);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_spaces');
    }
};