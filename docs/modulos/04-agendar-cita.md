# Entregable 4 · Agendar cita (sin pago)

**Objetivo:** que el paciente elija especialista, fecha y hora, confirme los datos y se
cree una cita en estado `pending_payment` que reserva el cupo. (El pago llega en el 5.)

## Alcance

- Pantalla de selección de **fecha y hora** a partir de los cupos disponibles
  (usa el `AvailabilityService` del entregable 3).
- Pantalla de **detalle/confirmación de la cita** (como el mockup: doctor, fecha, hora,
  tipo On-line, precio, nota "Tu información está protegida") con botón **Confirmar cita**.
- Al confirmar: se crea la cita `pending_payment`, se reserva el cupo y se fija
  `expires_at = ahora + 15 min`.
- Manejo de concurrencia: dos pacientes no pueden tomar el mismo cupo.
- Job programado que **expira** citas no pagadas y libera el cupo.

## Modelo de datos involucrado

- `appointments` (ver [02-modelo-de-datos](../02-modelo-de-datos.md))

## Pasos

1. Crear migración y modelo `Appointment` (con índice único sobre `doctor_id` + `starts_at`
   para citas activas).
2. Crear `BookingController`:
   - `selectSlot(doctor)` → muestra cupos disponibles (días navegables, horas del día).
   - `review(request)` → muestra resumen antes de confirmar.
   - `store(request)` → valida y crea la cita.
3. Vistas Blade:
   - `agendar/horario.blade.php` (selector de fecha + cupos de hora, con Alpine).
   - `agendar/confirmar.blade.php` (detalle de la cita + botón Confirmar).
4. En `store()`:
   - Revalidar que el cupo siga libre (defensa contra doble clic / carrera).
   - Usar **transacción de BD + bloqueo** (`lockForUpdate` o `insert` que falle por el
     índice único) para evitar doble reserva.
   - Congelar `price_cop` desde el doctor.
   - Crear la cita `pending_payment` con `expires_at`.
   - Redirigir a la pantalla de pago (placeholder hasta el entregable 5; por ahora puede
     marcarse como confirmada manualmente para demo).
5. Crear `ExpirePendingAppointments` (job/command) y agendarlo en el Scheduler cada minuto:
   marca como `expired` las citas `pending_payment` con `expires_at < ahora`.

## Reglas

- Solo usuarios autenticados pueden agendar (middleware `auth`).
- El precio se **congela** en la cita al crearla.
- Si el cupo ya fue tomado entre que se mostró y se confirmó → mensaje claro
  ("Ese horario ya no está disponible, elige otro") y volver a la selección.
- `expires_at` = creación + **15 minutos**.

## Criterios de aceptación

- [ ] Desde el perfil del doctor puedo elegir un día y ver las horas disponibles.
- [ ] Al elegir hora veo el resumen (doctor, fecha, hora, tipo, precio).
- [ ] Al confirmar se crea la cita `pending_payment` y el cupo desaparece de disponibles.
- [ ] Si dos personas intentan el mismo cupo, solo una lo obtiene; la otra ve el error.
- [ ] Una cita `pending_payment` sin pagar pasa a `expired` tras 15 min y libera el cupo.
- [ ] Funciona en celular.

## Entregable funcional

**Primer flujo demostrable de punta a punta** (registro → explorar → agendar). Ya se
puede validar con usuarios reales aunque todavía no se cobre.
