# 03 · Roadmap de entregables

El desarrollo avanza en **9 entregables** (0 a 8). Cada uno produce algo funcional
que puedes correr y demostrar. **No pases al siguiente hasta cumplir los criterios de
aceptación** del actual (definidos en cada módulo).

La idea: si el proyecto se detiene en cualquier punto a partir del entregable 4, ya
tienes algo usable; a partir del 5, ya cobras.

## Orden y dependencias

```
0 Cimientos
   └─▶ 1 Registro y acceso
          └─▶ 2 Catálogo (especialidades + especialistas)
                 └─▶ 3 Disponibilidad
                        └─▶ 4 Agendar cita (sin pago)   ◀── primer flujo "demo-able"
                               └─▶ 5 Pagos (Mercado Pago) ◀── primer flujo que monetiza
                                      └─▶ 6 Mis citas + notificaciones
                                             └─▶ 7 Panel especialista/admin
                                                    └─▶ 8 Pulido + publicación (PWA, deploy)
```

## Tabla de entregables

| # | Módulo | Objetivo en una frase | Estimación* |
|---|--------|----------------------|-------------|
| 0 | [Cimientos](modulos/00-cimientos.md) | Laravel corriendo + layout responsive estilo del mockup | 1–2 días |
| 1 | [Registro y acceso](modulos/01-autenticacion.md) | Registro, login, logout, recuperar contraseña | 1 día |
| 2 | [Catálogo](modulos/02-catalogo.md) | Especialidades y perfiles de especialistas | 2–3 días |
| 3 | [Disponibilidad](modulos/03-disponibilidad.md) | Horarios por doctor y cálculo de cupos libres | 2 días |
| 4 | [Agendar cita](modulos/04-agendar-cita.md) | Reservar fecha/hora → cita `pending_payment` | 2–3 días |
| 5 | [Pagos](modulos/05-pagos.md) | Cobro con Mercado Pago + webhook → `confirmed` | 3–4 días |
| 6 | [Mis citas + notificaciones](modulos/06-mis-citas.md) | Panel "Mis citas", correos, enlace de la consulta | 2–3 días |
| 7 | [Panel admin/especialista](modulos/07-panel-admin.md) | Gestión de doctores, agenda y citas | 3–4 días |
| 8 | [Pulido + deploy](modulos/08-pulido-deploy.md) | PWA, seguridad, pruebas, producción | 2–3 días |

\* Estimación orientativa para un desarrollador. Total aproximado: **3–4 semanas** a un MVP estable.

## Hitos de demostración

- **Demo 1 (fin de entregable 4):** un usuario registrado agenda una cita y queda
  reservada. Sin cobrar todavía. Sirve para validar el flujo con usuarios reales.
- **Demo 2 (fin de entregable 5):** flujo completo con pago real (en modo sandbox de
  Mercado Pago primero, luego producción). **Aquí ya se puede lanzar a un grupo cerrado.**
- **Lanzamiento (fin de entregable 8):** público, instalable en el celular, en tu dominio.

## Definición de "Hecho" (Definition of Done) por entregable

Cada entregable se considera terminado cuando:

1. Cumple **todos** los criterios de aceptación de su módulo.
2. Funciona correctamente en **celular** (Chrome Android y Safari iOS) y escritorio.
3. Las entradas del usuario están **validadas** y los errores se muestran claros.
4. El código está commiteado en Git con un mensaje descriptivo.
5. No rompe entregables anteriores (regresión mínima probada a mano).

## Recomendación de ejecución

- Trabaja **un módulo a la vez**, en orden.
- Después del entregable 5, despliega a un entorno de **staging** y prueba pagos en
  sandbox antes de tocar producción.
- Deja la integración real de Mercado Pago en **modo prueba** hasta validar el webhook.
