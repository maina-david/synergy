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
        Schema::create('folders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')
                ->constrained('organizations', 'id')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users', 'id')
                ->onDelete('cascade');
            $table->foreignUuid('parent_id')
                ->nullable()
                ->constrained('folders', 'id')
                ->onDelete('cascade');
            $table->string('name');
            $table->boolean('is_being_moved')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};