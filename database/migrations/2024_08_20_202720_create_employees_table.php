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
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')
                ->constrained('organizations', 'id')
                ->onDelete('cascade');
            $table->foreignUuid('user_id')
                ->constrained('users', 'id')
                ->onDelete('cascade');
            $table->foreignUuid('department_id')
                ->nullable()
                ->constrained('departments', 'id')
                ->onDelete('cascade');
            $table->foreignUuid('reports_to')
                ->constrained('employees', 'id')
                ->onDelete('cascade');
            $table->string('honorific')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('alternative_phone')->nullable()->unique();
            $table->longText('address')->nullable();
            $table->string('job_title')->nullable();
            $table->date('hire_date')->nullable();
            $table->decimal('salary', 5, 2)->nullable();
            $table->string('type');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};