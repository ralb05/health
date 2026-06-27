<?php

namespace App\Services;

use App\Models\Doctor;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AvailabilityService
{
    /**
     * Cupos libres de un especialista entre dos fechas.
     *
     * Genera los cupos a partir de sus horarios (schedules), descartando:
     *  - los que ya pasaron o no cumplen la anticipación mínima,
     *  - los que ya están ocupados por una cita activa.
     *
     * @return Collection<int, Carbon>  Lista ordenada de inicios de cupo.
     */
    public function slotsFor(Doctor $doctor, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $from = $from ? $from->copy()->startOfDay() : Carbon::now()->startOfDay();
        $to = $to ? $to->copy()->endOfDay()
            : Carbon::now()->addDays((int) config('booking.horizon_days', 30))->endOfDay();

        $earliest = Carbon::now()->addHours((int) config('booking.min_anticipation_hours', 2));

        // Horarios del doctor agrupados por día de la semana (0=Dom … 6=Sáb).
        $schedulesByWeekday = $doctor->activeSchedules()
            ->orderBy('start_time')
            ->get()
            ->groupBy('weekday');

        if ($schedulesByWeekday->isEmpty()) {
            return collect();
        }

        $taken = $this->takenSlots($doctor, $from, $to);

        $slots = collect();

        foreach (CarbonPeriod::create($from, '1 day', $to) as $day) {
            $daySchedules = $schedulesByWeekday->get($day->dayOfWeek);

            if (! $daySchedules) {
                continue;
            }

            foreach ($daySchedules as $schedule) {
                $cursor = $day->copy()->setTimeFromTimeString($schedule->start_time);
                $blockEnd = $day->copy()->setTimeFromTimeString($schedule->end_time);
                $step = $schedule->slot_minutes;

                while ($cursor->copy()->addMinutes($step)->lessThanOrEqualTo($blockEnd)) {
                    if ($cursor->greaterThanOrEqualTo($earliest)
                        && ! $taken->has($cursor->format('Y-m-d H:i'))) {
                        $slots->push($cursor->copy());
                    }

                    $cursor->addMinutes($step);
                }
            }
        }

        return $slots->sort()->values();
    }

    /**
     * Igual que slotsFor() pero agrupado por día (clave 'Y-m-d').
     * Útil para pintar el selector de fecha/hora en el Entregable 4.
     *
     * @return Collection<string, Collection<int, Carbon>>
     */
    public function slotsByDay(Doctor $doctor, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        return $this->slotsFor($doctor, $from, $to)
            ->groupBy(fn (Carbon $slot) => $slot->format('Y-m-d'));
    }

    /**
     * ¿El datetime dado es un cupo libre y válido ahora mismo?
     * (Dentro de un horario, no en el pasado, con anticipación, y no ocupado.)
     */
    public function isAvailable(Doctor $doctor, Carbon $slot): bool
    {
        return $this->slotsFor($doctor, $slot->copy()->startOfDay(), $slot->copy()->endOfDay())
            ->contains(fn (Carbon $s) => $s->equalTo($slot));
    }

    /**
     * Duración (minutos) del cupo que empieza en $slot según el horario del doctor,
     * o null si ese inicio no corresponde a ningún horario activo.
     */
    public function slotMinutesFor(Doctor $doctor, Carbon $slot): ?int
    {
        $schedule = $doctor->activeSchedules()
            ->where('weekday', $slot->dayOfWeek)
            ->get()
            ->first(function ($schedule) use ($slot) {
                $start = $slot->copy()->setTimeFromTimeString($schedule->start_time);
                $end = $slot->copy()->setTimeFromTimeString($schedule->end_time);

                return $slot->greaterThanOrEqualTo($start) && $slot->lessThan($end);
            });

        return $schedule?->slot_minutes;
    }

    /**
     * Cupos ya ocupados por citas activas (pendiente de pago o confirmada),
     * indexados por 'Y-m-d H:i' para descartarlos rápido.
     *
     * @return Collection<string, bool>
     */
    protected function takenSlots(Doctor $doctor, Carbon $from, Carbon $to): Collection
    {
        // La tabla de citas se crea en el Entregable 4; antes de eso no hay nada que excluir.
        if (! Schema::hasTable('appointments')) {
            return collect();
        }

        return DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', ['pending_payment', 'confirmed'])
            ->whereBetween('starts_at', [$from, $to])
            ->pluck('starts_at')
            ->mapWithKeys(fn ($startsAt) => [Carbon::parse($startsAt)->format('Y-m-d H:i') => true]);
    }
}
