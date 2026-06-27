# Entregable 6 · Mis citas y notificaciones

**Objetivo:** que el paciente vea y gestione sus citas, reciba correos (confirmación y
recordatorio) y obtenga el **enlace de la videollamada** que comparte el especialista.

## Alcance

- Pantalla **"Mis citas"** (pestaña "Citas" del mockup): listado de citas próximas y
  pasadas, con estado, doctor, fecha/hora y precio.
- **Detalle de la cita** con: datos del especialista, fecha, hora, tipo On-line, precio,
  estado, nota "Tu información está protegida" y el **enlace de la consulta** (cuando exista).
- Bloque **"Próxima cita"** en la pantalla de inicio (la que era placeholder en el módulo 2).
- **Cancelar cita** por parte del paciente (con regla de anticipación mínima).
- **Correos** transaccionales: confirmación de cita y recordatorio.
- Mecanismo para que el especialista pegue el `meeting_url` (campo de la cita; la UI del
  especialista se completa en el entregable 7, pero el campo ya se usa aquí).

## Pasos

1. Crear `AppointmentController@index` (mis citas) y `@show` (detalle), protegidos por
   `auth` y con **policy**: el paciente solo ve sus propias citas.
2. Vistas:
   - `citas/index.blade.php` (próximas / pasadas).
   - `citas/show.blade.php` (detalle con enlace de la consulta si está disponible).
3. Mostrar el bloque "Próxima cita" en `inicio` (la cita confirmada más cercana).
4. Cancelación: `@cancel` que pasa la cita a `cancelled` y libera el cupo, solo si falta
   más de X horas (configurable, ej. 24 h).
5. Correos (Mailable + cola):
   - `AppointmentConfirmed` (al confirmarse el pago, disparado desde el entregable 5).
   - `AppointmentReminder` (enviado por el Scheduler, ej. 24 h y/o 1 h antes).
6. Configurar el **Scheduler** para enviar recordatorios de citas próximas.
7. Mostrar el `meeting_url` en el detalle solo si la cita está `confirmed` y el enlace existe.

## Reglas

- Un paciente solo ve y gestiona **sus** citas (policy/autorización).
- Cancelación permitida solo con la anticipación mínima configurada.
- El enlace de la consulta se muestra cuando el especialista lo ha cargado; si no, se
  indica "El especialista te enviará el enlace antes de la cita".
- Los correos salen por **cola** para no bloquear la petición.

## Criterios de aceptación

- [ ] En "Mis citas" veo mis citas próximas y pasadas con su estado.
- [ ] El detalle muestra toda la info y, si está confirmada con enlace, el botón/enlace de la consulta.
- [ ] La pantalla de inicio muestra mi próxima cita.
- [ ] Al confirmarse una cita recibo un correo de confirmación.
- [ ] Recibo un recordatorio antes de la cita.
- [ ] Puedo cancelar una cita dentro de la regla de anticipación y el cupo se libera.

## Entregable funcional

El paciente ya tiene una experiencia completa post-compra: ve sus citas, recibe
notificaciones y accede al enlace de la consulta. **Cierra el ciclo del paciente.**
