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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('author_id')
                ->constrained('users', 'id')
                ->onDelete('cascade');
            $table->foreignUuid('organization_id')
                ->constrained('organizations', 'id')
                ->onDelete('cascade');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->decimal('budget', 5, 2)->nullable()->default(0.00);
            $table->string('priority');
            $table->string('status');
            $table->dateTime('last_updated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};