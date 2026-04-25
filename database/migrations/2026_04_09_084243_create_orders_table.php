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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('restrict');
            $table->string('order_number', 20)->unique(); // #IVS-2025-0001
            $table->string('status')->default('pending');
            $table->string('payment_method', 50)->nullable(); // [
            $table->string('payment_reference')->nullable(); // Ref paiement externe
            $table->string('payment_status')->default('unpaid');
            $table->decimal('subtotal', 10, 0);           // Avant frais livraison
            $table->decimal('delivery_fee', 10, 0)->default(0);
            $table->decimal('discount_amount', 10, 0)->default(0);
            $table->decimal('total', 10, 0);              // Montant final
            $table->text('notes')->nullable();            // Note du client
            $table->json('shipping_address');             // Adresse livraison au moment de la commande
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index('order_number');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
