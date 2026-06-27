# Entregable 5 · Pagos (Mercado Pago)

**Objetivo:** cobrar la cita en línea con **Mercado Pago**. Cuando el pago se aprueba,
la cita pasa de `pending_payment` a `confirmed`. Es el módulo que **monetiza** el MVP.

## Alcance

- Integración con **Mercado Pago Checkout Pro** (redirección a la pasarela) en **COP**.
- Creación de una "preferencia" de pago por cita.
- Páginas de retorno: éxito, pendiente, fallo.
- **Webhook** (notificación servidor-a-servidor) que confirma el pago de forma confiable
  (no se confía solo en la redirección del navegador).
- Registro del pago en la tabla `payments`.
- Transición de la cita a `confirmed` y cancelación del `expires_at`.

> Se empieza en **modo sandbox** (credenciales de prueba) y solo se pasa a producción
> cuando el webhook funciona de extremo a extremo.

## Modelo de datos involucrado

- `payments` (ver [02-modelo-de-datos](../02-modelo-de-datos.md))
- Actualiza `appointments.status`.

## Configuración previa

1. Crear cuenta en Mercado Pago Colombia y obtener credenciales:
   - `MP_PUBLIC_KEY`, `MP_ACCESS_TOKEN` (sandbox primero).
2. Añadirlas al `.env` y a `config/services.php`.
3. Instalar SDK: `composer require mercadopago/dx-php`.

## Pasos

1. Crear migración y modelo `Payment` (relación `Appointment hasOne Payment`).
2. Crear `MercadoPagoService` con:
   - `createPreference(Appointment $a)`: arma la preferencia (ítem = consulta con
     `unit_price = price_cop`, `currency_id = COP`, `back_urls`, `notification_url`,
     `external_reference = appointment_id`). Devuelve `init_point` (URL de checkout).
3. Crear `PaymentController`:
   - `checkout(appointment)`: crea la preferencia, guarda `preference_id`, redirige al
     `init_point` de Mercado Pago.
   - `success` / `pending` / `failure`: páginas de retorno para el usuario.
   - `webhook(request)`: recibe la notificación de MP, consulta el pago por su id,
     valida, actualiza `payments` y, si `approved`, pasa la cita a `confirmed` y limpia
     `expires_at`.
4. Excluir la ruta del **webhook** de la protección CSRF y de `auth`.
5. Vistas de retorno: éxito ("¡Cita confirmada!"), pendiente, fallo (con opción de reintentar).
6. Idempotencia: si el webhook llega dos veces para el mismo pago, no duplicar ni
   re-procesar (verificar por `provider_payment_id`).
7. Disparar el **correo de confirmación** (entregable 6) cuando la cita pase a `confirmed`.

## Reglas y consideraciones de seguridad

- La confirmación **oficial** del pago es el **webhook**, no la redirección del navegador.
- Verificar que el monto pagado coincide con `appointment.price_cop`.
- Guardar `raw_payload` del webhook para auditoría.
- Nunca exponer el `MP_ACCESS_TOKEN` en el frontend.
- Si el pago es rechazado, la cita sigue `pending_payment` (y puede expirar) — el cupo no
  se confirma.

## Criterios de aceptación

- [ ] Desde la cita `pending_payment` puedo ir a pagar y soy redirigido a Mercado Pago.
- [ ] Pagando en sandbox con tarjeta de prueba, regreso a la página de éxito.
- [ ] El **webhook** marca la cita como `confirmed` aunque cierre el navegador.
- [ ] Se crea un registro en `payments` con estado `approved` y `paid_at`.
- [ ] Un webhook repetido no duplica ni rompe nada (idempotente).
- [ ] El monto cobrado coincide con el precio de la cita.

## Entregable funcional

**Flujo completo monetizado**: registro → agendar → pagar → cita confirmada. Con esto el
producto ya genera ingresos y se puede lanzar a un grupo cerrado.
