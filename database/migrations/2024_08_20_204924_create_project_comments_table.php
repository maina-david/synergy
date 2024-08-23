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
        Schema::create('project_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')
                ->constrained('projects', 'id')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users', 'id')
                ->onDelete('cascade');
            $table->foreignUuid('parent_id')
                ->constrained('project_comments', 'id')
                ->onDelete('cascade');
            $table->longText('comment');
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_comments');
    }
};