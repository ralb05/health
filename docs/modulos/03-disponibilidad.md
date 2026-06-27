# Entregable 3 · Disponibilidad

**Objetivo:** que cada especialista tenga horarios de atención y que el sistema sepa
calcular qué **cupos (fecha + hora)** están libres para agendar.

## Alcance

- Definición de disponibilidad por especialista (recurrencia semanal: día, hora inicio,
  hora fin, duración de cada cupo).
- Servicio que, dado un especialista y un rango de fechas, devuelve los **cupos disponibles**
  (los del horario, menos los ya ocupados por citas activas).
- Carga de disponibilidad de ejemplo vía seeder.

> En el MVP la disponibilidad se administra por seeder/tinker o, más adelante, desde el
> panel del especialista (entregable 7). Este módulo entrega la **lógica de cálculo**,
> que es lo que el agendamiento (entregable 4) necesita.

## Modelo de datos involucrado

- `schedules` (ver [02-modelo-de-datos](../02-modelo-de-datos.md))
- Lectura de `appointments` para descartar cupos ocupados.

## Pasos

1. Crear migración y modelo `Schedule` (relación `Doctor hasMany Schedule`).
2. Seeder de disponibilidad: para cada doctor, lun–vie 08:00–12:00, `slot_minutes = 60`.
3. Crear `AvailabilityService` con un método tipo
   `slotsFor(Doctor $doctor, Carbon $from, Carbon $to): array`:
   - Recorre los días del rango.
   - Para cada día, busca los `schedules` de ese `weekday`.
   - Genera los cupos partiendo `start_time`–`end_time` en bloques de `slot_minutes`.
   - Descarta cupos en el pasado y cupos ya tomados por citas con estado
     `pending_payment` o `confirmed`.
   - Devuelve la lista de cupos libres (datetime de inicio).
4. Pruebas manuales con `php artisan tinker` para validar el cálculo.

## Reglas

- No se ofrecen cupos en el **pasado** ni el mismo día con menos de X horas (configurable,
  ej. 2 horas de anticipación mínima).
- Un cupo se considera ocupado si existe una cita activa (`pending_payment` o `confirmed`)
  con ese `doctor_id` + `starts_at`.
- Horizonte de agendamiento por defecto: próximos **30 días**.

## Criterios de aceptación

- [ ] Existe disponibilidad cargada para los doctores de ejemplo.
- [ ] `AvailabilityService` devuelve cupos correctos para un doctor y rango dado.
- [ ] Los cupos en el pasado no aparecen.
- [ ] Si creo manualmente una cita en un cupo, ese cupo deja de aparecer como libre.

## Entregable funcional

La "inteligencia de agenda": el sistema ya sabe cuándo puede atender cada especialista.
Sin UI aún de paciente (eso llega en el entregable 4), pero verificable por tinker.
