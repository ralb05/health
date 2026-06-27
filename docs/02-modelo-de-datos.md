# 02 · Modelo de datos

Esquema mínimo para el MVP. Se construye por partes a lo largo de los entregables
(no hace falta crear todo de una vez). Motor: MySQL/MariaDB.

## Diagrama de relaciones (texto)

```
users ──1:1── doctors                (un user con rol "doctor" tiene un perfil doctor)
specialties ──1:N── doctors          (una especialidad tiene muchos doctores)
doctors ──1:N── schedules            (un doctor define muchos bloques de disponibilidad)
users(patient) ──1:N── appointments  (un paciente tiene muchas citas)
doctors ──1:N── appointments         (un doctor tiene muchas citas)
appointments ──1:1── payments        (una cita tiene un pago)
```

## Tablas

### `users` (base de Breeze, se amplía)
Cuentas de acceso para los 3 roles.

| Campo | Tipo | Notas |
|-------|------|-------|
| id | bigint PK | |
| name | string | |
| email | string unique | |
| password | string | hash |
| role | enum(`patient`,`doctor`,`admin`) | default `patient` |
| phone | string null | celular (para WhatsApp/recordatorio) |
| email_verified_at | timestamp null | verificación de correo |
| timestamps | | |

### `specialties` — especialidades
Ej.: Psiquiatría, Psicología.

| Campo | Tipo | Notas |
|-------|------|-------|
| id | bigint PK | |
| name | string | "Psiquiatría" |
| slug | string unique | "psiquiatria" |
| description | text null | "Atención médica especializada para tu bienestar mental." |
| icon | string null | nombre de ícono o ruta |
| is_active | boolean | default true |
| timestamps | | |

### `doctors` — perfil del especialista
Datos públicos del especialista (1:1 con un `user` de rol `doctor`).

| Campo | Tipo | Notas |
|-------|------|-------|
| id | bigint PK | |
| user_id | FK users | nullable hasta que se le cree acceso |
| specialty_id | FK specialties | |
| full_name | string | "Dra. Sofía Álvarez" |
| title | string null | "Psiquiatra" |
| bio | text null | descripción del perfil |
| photo_url | string null | foto |
| experience_years | int | "8 años de experiencia" |
| rating | decimal(2,1) null | "5.0" (carga manual en MVP) |
| reviews_count | int default 0 | "120 reseñas" |
| price_cop | int | precio de la consulta en pesos, ej. 120000 |
| tags | json null | ["Adultos","Adolescentes"] |
| is_active | boolean default true | |
| timestamps | | |

### `schedules` — disponibilidad del especialista
Define en qué bloques atiende. Para el MVP, **recurrencia semanal**.

| Campo | Tipo | Notas |
|-------|------|-------|
| id | bigint PK | |
| doctor_id | FK doctors | |
| weekday | tinyint (0=Dom … 6=Sáb) | día de la semana |
| start_time | time | ej. 08:00 |
| end_time | time | ej. 12:00 |
| slot_minutes | int default 60 | duración de cada cupo |
| is_active | boolean default true | |
| timestamps | | |

> Los **cupos** (slots) NO se guardan: se calculan en el aire a partir de `schedules`
> menos las citas ya tomadas (ver módulo 03). Opcional: tabla `schedule_exceptions`
> para bloquear días puntuales (vacaciones) — se puede dejar para fase 2.

### `appointments` — citas
El corazón del sistema.

| Campo | Tipo | Notas |
|-------|------|-------|
| id | bigint PK | |
| patient_id | FK users | paciente |
| doctor_id | FK doctors | especialista |
| specialty_id | FK specialties | denormalizado para reportes |
| starts_at | datetime | fecha y hora de inicio (cupo) |
| ends_at | datetime | calculado con slot_minutes |
| type | enum(`online`) | en MVP solo online |
| status | enum(`pending_payment`,`confirmed`,`completed`,`cancelled`,`expired`) | |
| price_cop | int | precio **congelado** al agendar |
| meeting_url | string null | enlace de videollamada que pega el doctor |
| notes | text null | nota opcional del paciente al agendar |
| expires_at | datetime null | cuándo se libera el cupo si no paga (15 min) |
| timestamps | | |

Índice único recomendado: `(doctor_id, starts_at)` sobre citas activas para evitar
doble reserva del mismo cupo.

### `payments` — pagos
Un registro por intento/resultado de pago (1:1 con la cita en el caso feliz).

| Campo | Tipo | Notas |
|-------|------|-------|
| id | bigint PK | |
| appointment_id | FK appointments | |
| provider | string default 'mercadopago' | |
| provider_payment_id | string null | id del pago en Mercado Pago |
| preference_id | string null | id de la preferencia/checkout |
| amount_cop | int | monto cobrado |
| status | enum(`pending`,`approved`,`rejected`,`refunded`) | mapea estado de MP |
| raw_payload | json null | respuesta cruda del webhook (auditoría) |
| paid_at | datetime null | |
| timestamps | | |

## Estados de una cita (máquina de estados)

```
pending_payment ──(pago aprobado)──▶ confirmed ──(pasa la consulta)──▶ completed
       │                                  │
       │(15 min sin pagar)                │(paciente o admin cancela)
       ▼                                  ▼
    expired                           cancelled
```

- `pending_payment`: creada, cupo reservado temporalmente, esperando pago.
- `confirmed`: pago aprobado; cupo asegurado; se notifica por correo.
- `completed`: la cita ya ocurrió (se marca manual o por fecha pasada).
- `cancelled`: cancelada por el paciente/admin.
- `expired`: no se pagó a tiempo; el cupo se libera.

## Datos semilla (seeders) para el MVP

- 2 especialidades: **Psiquiatría**, **Psicología**.
- 3–4 especialistas de ejemplo (como el mockup: Dra. Sofía Álvarez, Dr. Andrés Martínez,
  Dra. Camila Restrepo…) con foto, rating, experiencia y precio.
- Disponibilidad de ejemplo para cada especialista (lun–vie, 08:00–12:00, cupos de 60 min).
- 1 usuario admin de prueba.
