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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specialty_id')->constrained();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('type')->default('online'); // online | (presencial en el futuro)
            $table->string('status')->default('pending_payment');
            // pending_payment | confirmed | completed | cancelled | expired
            $table->unsignedInteger('price_cop'); // precio congelado al agendar
            $table->string('meeting_url')->nullable(); // enlace de la videollamada (E6)
            $table->text('notes')->nullable();
            $table->dateTime('expires_at')->nullable(); // cuándo se libera el cupo si no se paga

            // Clave de reserva: evita dos citas ACTIVAS en el mismo cupo.
            // Es null cuando la cita se cancela/expira, así el cupo se puede volver a tomar.
            $table->string('reservation_key')->nullable()->unique();

            $table->timestamps();

            $table->index(['doctor_id', 'starts_at']);
            $table->index(['patient_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
