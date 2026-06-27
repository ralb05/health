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
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->default('mercadopago');
            $table->string('provider_payment_id')->nullable()->index(); // id del pago en MP
            $table->string('preference_id')->nullable();                // id de la preferencia/checkout
            $table->unsignedInteger('amount_cop');
            $table->string('status')->default('pending'); // pending | approved | rejected | refunded
            $table->json('raw_payload')->nullable();       // respuesta cruda (auditoría)
            $table->dateTime('paid_at')->nullable();
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
