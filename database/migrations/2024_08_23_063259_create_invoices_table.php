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
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')
                ->constrained('organizations', 'id')
                ->onDelete('cascade');
            $table->foreignUuid('module_id')
                ->constrained('modules', 'id')
                ->onDelete('cascade');
            $table->string('subscription_type');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('status');
            $table->date('due_date');
            $table->timestamp('issued_at')->nullable(); 
            $table->timestamp('paid_at')->nullable();  
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};