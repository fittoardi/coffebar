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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('method'); // online, cash, qris, transfer
            $table->string('status')->default('pending'); // pending, paid, failed, expired
            $table->string('gateway_name')->nullable(); // midtrans
            $table->string('gateway_reference')->nullable();
            $table->json('gateway_payload')->nullable();
            $table->decimal('cash_given', 12, 2)->nullable();
            $table->decimal('change_given', 12, 2)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
