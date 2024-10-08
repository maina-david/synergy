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
        Schema::create('employee_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')
                ->constrained('employees', 'id')
                ->onDelete('cascade');
            $table->foreignUuid('user_id')
                ->constrained('users', 'id')
                ->onDelete('cascade');
            $table->foreignUuid('file_id')
                ->constrained('files', 'id')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_files');
    }
};