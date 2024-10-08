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
        Schema::create('modules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('module_category_id')
                ->constrained('module_categories', 'id')
                ->onDelete('cascade');
            $table->string('name');
            $table->longText('description');
            $table->string('url');
            $table->string('icon')->nullable();
            $table->string('banner')->nullable();
            $table->string('subscription_type');
            $table->decimal('price', 5, 2)->default(0.0);
            $table->boolean('active')->nullable()->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};