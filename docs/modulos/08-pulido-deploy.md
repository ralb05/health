# Entregable 8 · Pulido y publicación

**Objetivo:** dejar el MVP estable, seguro, instalable en el celular (PWA) y desplegado en
producción bajo tu dominio.

## Alcance

- **PWA**: manifest + service worker para que la web se instale como app en el celular
  (ícono en pantalla, splash, abre a pantalla completa).
- **Seguridad y privacidad** (datos de salud → sensibles).
- **Pruebas** del flujo crítico de punta a punta.
- **Despliegue** a producción + pago de Mercado Pago en modo real.

## PWA

1. Crear `public/manifest.webmanifest` (nombre "Mind & Health", íconos, `theme_color`,
   `display: standalone`).
2. Service worker básico (cachear assets estáticos; **no** cachear datos sensibles ni
   respuestas de citas/pagos).
3. Enlazar el manifest en el layout y probar "Agregar a pantalla de inicio" en Android/iOS.

## Seguridad y privacidad (mínimos para datos de salud)

- **HTTPS obligatorio** en producción (certificado válido).
- Contraseñas con hash (ya por Breeze), CSRF activo, rate limiting en login.
- Validación y saneamiento de **todas** las entradas; Eloquent evita SQL injection,
  Blade escapa HTML por defecto (evitar `{!! !!}` con datos de usuario).
- Autorización por **policies** revisada (cada quien ve solo lo suyo).
- Cabeceras de seguridad (HSTS, X-Frame-Options, etc.).
- Secretos solo en `.env` (nunca en el repo). Rotar llaves antes de producción.
- Webhook de pagos validado (origen/firma según Mercado Pago) e idempotente.
- Página de **Términos** y **Política de privacidad / tratamiento de datos** (en Colombia,
  Ley 1581 de protección de datos). Casilla de aceptación en el registro.
- Backups automáticos de la base de datos.

## Pruebas del flujo crítico

- [ ] Registro → login.
- [ ] Explorar especialistas → agendar → pagar (sandbox) → cita confirmada.
- [ ] Webhook confirma aunque se cierre el navegador.
- [ ] Cita no pagada expira y libera el cupo.
- [ ] Correos de confirmación y recordatorio salen.
- [ ] Doctor carga enlace y el paciente lo ve.
- [ ] Probado en Chrome Android y Safari iOS reales.

## Despliegue

1. Elegir hosting (opciones que encajan con Laravel + MySQL):
   - VPS (DigitalOcean/Hetzner/Linode) con Apache/Nginx + PHP-FPM + MySQL — más control.
   - **Laravel Forge** + VPS — despliegue gestionado, recomendado para mantenerlo tú solo.
   - Hosting compartido con soporte Laravel — el más barato, menos flexible.
2. Configurar `.env` de producción (`APP_ENV=production`, `APP_DEBUG=false`,
   `APP_TIMEZONE=America/Bogota`, BD, SMTP real, llaves MP de producción).
3. `composer install --no-dev`, `php artisan migrate --force`,
   `php artisan config:cache route:cache view:cache`, `npm run build`.
4. Configurar el **worker de colas** (Supervisor) y el **Scheduler** (cron
   `* * * * * php artisan schedule:run`).
5. Apuntar el dominio, instalar **SSL** (Let's Encrypt).
6. Pasar Mercado Pago a **producción**; registrar la URL del webhook pública.
7. Smoke test en producción con una compra real de bajo monto.

## Criterios de aceptación

- [ ] La web se instala como app en el celular (PWA) y abre a pantalla completa.
- [ ] El sitio corre en producción bajo HTTPS en tu dominio.
- [ ] Colas y Scheduler activos (correos y expiración de citas funcionan en prod).
- [ ] Pago real de Mercado Pago confirmado por webhook en producción.
- [ ] Existen Términos y Política de datos, aceptados en el registro.
- [ ] Backups de BD configurados.

## Entregable funcional

**MVP estable, seguro y publicado.** Listo para usuarios reales, instalable en el celular,
con pagos reales y la operación administrable desde el panel.
