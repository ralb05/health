# Mind & Health — Documentación del MVP

App web responsive de salud mental: el paciente se registra, agenda una cita con un
especialista (psiquiatría / psicología), paga en línea y recibe la confirmación.
La consulta se realiza por videollamada mediante un **enlace externo** (Meet/Zoom/WhatsApp)
que el especialista comparte antes de la cita.

> **Una sola base de código web** (Laravel + Blade) que funciona perfecto en celular,
> tablet y escritorio. No hay app nativa en el MVP.

## Cómo leer esta documentación

Lee en este orden:

1. [`00-vision-general.md`](00-vision-general.md) — Qué es, para quién, alcance del MVP y qué queda fuera.
2. [`01-stack-tecnologico.md`](01-stack-tecnologico.md) — Tecnologías, por qué, y cómo levantar el entorno.
3. [`02-modelo-de-datos.md`](02-modelo-de-datos.md) — Tablas, relaciones y diccionario de datos.
4. [`03-roadmap-entregables.md`](03-roadmap-entregables.md) — El plan paso a paso. **Empieza aquí para construir.**
5. [`modulos/`](modulos/) — Especificación detallada de cada módulo/entregable.

## Principio rector

Cada módulo de la carpeta [`modulos/`](modulos/) es un **entregable funcional**: al
terminarlo, tienes algo que puedes correr, ver y demostrar. No se pasa al siguiente
hasta que el anterior cumple sus **criterios de aceptación**.

| # | Entregable | Resultado visible |
|---|-----------|-------------------|
| 0 | [Cimientos](modulos/00-cimientos.md) | Proyecto Laravel corriendo, layout responsive, página de inicio |
| 1 | [Registro y acceso](modulos/01-autenticacion.md) | Te registras, inicias y cierras sesión |
| 2 | [Especialidades y especialistas](modulos/02-catalogo.md) | Navegas especialidades y ves el perfil de cada doctor |
| 3 | [Disponibilidad](modulos/03-disponibilidad.md) | Cada doctor tiene horarios; el sistema calcula cupos libres |
| 4 | [Agendar cita](modulos/04-agendar-cita.md) | Eliges doctor, fecha y hora; queda una cita "pendiente de pago" |
| 5 | [Pagos (Mercado Pago)](modulos/05-pagos.md) | Pagas la cita en línea y queda "confirmada" |
| 6 | [Mis citas y notificaciones](modulos/06-mis-citas.md) | Ves tus citas, recibes correos, el doctor comparte el enlace |
| 7 | [Panel del especialista/admin](modulos/07-panel-admin.md) | Doctores y admin gestionan agenda y citas |
| 8 | [Pulido y publicación](modulos/08-pulido-deploy.md) | PWA, seguridad, despliegue a producción |

## Estado del proyecto

- [x] Entregable 0 — Cimientos ✅ (Laravel 13 + Tailwind v4 + Alpine, pantalla de bienvenida funcionando)
- [x] Entregable 1 — Registro y acceso ✅ (Breeze backend + diseño propio: registro/login/logout/recuperar, campos role+phone, home /inicio)
- [x] Entregable 2 — Especialidades y especialistas ✅ (home con especialidades, lista y perfil con precio COP; seeders con 5 especialistas)
- [x] Entregable 3 — Disponibilidad ✅ (schedules por doctor + AvailabilityService que calcula cupos libres; config/booking.php)
- [x] Entregable 4 — Agendar cita ✅ (selector fecha/hora, cita pending_payment, reserva de cupo anti-doble-reserva, expiración a los 15 min)
- [x] Entregable 5 — Pagos ✅ (Mercado Pago: checkout + webhook idempotente que confirma la cita; modo simulado para probar sin llaves)
- [ ] Entregable 6 — Mis citas y notificaciones
- [ ] Entregable 7 — Panel del especialista/admin
- [ ] Entregable 8 — Pulido y publicación
