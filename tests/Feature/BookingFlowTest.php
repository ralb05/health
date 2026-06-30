<?php

namespace Tests\Feature;

use App\Mail\AppointmentConfirmed;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Payment;
use App\Models\Specialty;
use App\Models\User;
use App\Services\AvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeDoctorWithSchedule(): Doctor
    {
        $specialty = Specialty::create([
            'name' => 'Psiquiatría', 'slug' => 'psiquiatria', 'is_active' => true,
        ]);

        $doctor = Doctor::create([
            'specialty_id' => $specialty->id,
            'full_name' => 'Dra. Sofía Álvarez',
            'title' => 'Psiquiatra',
            'experience_years' => 8,
            'price_cop' => 120000,
            'is_active' => true,
        ]);

        // Disponibilidad todos los días para garantizar un cupo futuro.
        foreach (range(0, 6) as $weekday) {
            $doctor->schedules()->create([
                'weekday' => $weekday,
                'start_time' => '08:00',
                'end_time' => '18:00',
                'slot_minutes' => 60,
                'is_active' => true,
            ]);
        }

        return $doctor;
    }

    public function test_patient_can_register_book_and_pay(): void
    {
        Mail::fake();

        $doctor = $this->makeDoctorWithSchedule();

        // 1) Registro
        $this->post('/register', [
            'name' => 'Laura Gómez',
            'email' => 'laura@test.com',
            'phone' => '3001234567',
            'password' => 'claveSegura123',
            'password_confirmation' => 'claveSegura123',
            'terms' => '1',
        ])->assertRedirect(route('inicio'));

        $patient = User::where('email', 'laura@test.com')->firstOrFail();
        $this->assertSame('patient', $patient->role);

        // 2) Agendar el primer cupo disponible
        $slot = app(AvailabilityService::class)->slotsFor($doctor)->first();
        $this->assertNotNull($slot, 'Debe existir al menos un cupo disponible');

        $this->actingAs($patient)
            ->post(route('booking.store', $doctor), ['slot' => $slot->format('Y-m-d H:i')])
            ->assertRedirect();

        $appointment = Appointment::where('patient_id', $patient->id)->firstOrFail();
        $this->assertSame(Appointment::STATUS_PENDING, $appointment->status);
        $this->assertSame(120000, $appointment->price_cop);

        // 3) Checkout (modo simulado) y pago aprobado
        $this->actingAs($patient)->post(route('payment.checkout', $appointment))->assertRedirect();
        $this->actingAs($patient)
            ->post(route('payment.simulate', $appointment), ['result' => 'approved'])
            ->assertRedirect(route('payment.success', $appointment));

        // 4) La cita queda confirmada y el pago aprobado
        $appointment->refresh();
        $this->assertSame(Appointment::STATUS_CONFIRMED, $appointment->status);
        $this->assertNull($appointment->expires_at);
        $this->assertSame(Payment::STATUS_APPROVED, $appointment->payment->status);

        // 5) Se encola el correo de confirmación (los mailables implementan ShouldQueue)
        Mail::assertQueued(AppointmentConfirmed::class);
    }

    public function test_slot_cannot_be_double_booked(): void
    {
        $doctor = $this->makeDoctorWithSchedule();
        $slot = app(AvailabilityService::class)->slotsFor($doctor)->first();

        $a = User::factory()->create(['role' => 'patient']);
        $b = User::factory()->create(['role' => 'patient']);

        $this->actingAs($a)->post(route('booking.store', $doctor), ['slot' => $slot->format('Y-m-d H:i')]);
        $this->assertSame(1, Appointment::where('doctor_id', $doctor->id)->count());

        // El segundo paciente intenta el mismo cupo: no se crea otra cita.
        $this->actingAs($b)->post(route('booking.store', $doctor), ['slot' => $slot->format('Y-m-d H:i')]);
        $this->assertSame(1, Appointment::where('doctor_id', $doctor->id)->count());
    }

    public function test_patient_cannot_access_admin_panel(): void
    {
        $patient = User::factory()->create(['role' => 'patient']);

        $this->actingAs($patient)->get('/admin')->assertForbidden();
    }
}
