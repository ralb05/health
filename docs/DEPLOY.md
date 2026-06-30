# Guía de despliegue a producción — Mind & Health

MVP estable listo para publicar. Esta guía asume **Laravel 13 + MySQL/MariaDB + Mercado Pago**.

## 1. Elegir hosting

| Opción | Recomendado para |
|--------|------------------|
| **Laravel Forge + VPS** (DigitalOcean/Hetzner) | ✅ Más fácil de mantener tú solo (despliegue, SSL, colas y cron gestionados) |
| VPS manual (Nginx + PHP-FPM + MySQL) | Más control, más trabajo |
| Hosting compartido con soporte Laravel | El más barato, menos flexible |

Requisitos del servidor: **PHP 8.3+**, **MySQL 8 / MariaDB 10.4+**, Composer, Node 18+ (solo para compilar assets), Redis (opcional, para colas).

## 2. Subir el código

```bash
git clone <tu-repo> /var/www/health
cd /var/www/health
composer install --no-dev --optimize-autoloader
npm ci && npm run build
```

## 3. Variables de entorno (`.env` de producción)

Parte de [`.env.production.example`](../.env.production.example). Claves críticas:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.co
APP_TIMEZONE=America/Bogota
APP_LOCALE=es

# Base de datos MySQL/MariaDB
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=health
DB_USERNAME=health
DB_PASSWORD=********

# Colas y caché (database funciona; Redis recomendado a escala)
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true

# Correo real (Brevo, Mailgun, Amazon SES…)
MAIL_MAILER=smtp
MAIL_HOST=smtp.tu-proveedor.com
MAIL_PORT=587
MAIL_USERNAME=********
MAIL_PASSWORD=********
MAIL_FROM_ADDRESS="citas@tudominio.co"
MAIL_FROM_NAME="Mind & Health"

# Mercado Pago — credenciales de PRODUCCIÓN
MP_PUBLIC_KEY=APP_USR-...
MP_ACCESS_TOKEN=APP_USR-...
```

> Generar la clave de app: `php artisan key:generate`

## 4. Preparar la app

```bash
php artisan migrate --force
php artisan db:seed --class=CatalogSeeder   # opcional: datos iniciales
php artisan storage:link
php artisan config:cache route:cache view:cache
```

## 5. SSL / HTTPS (obligatorio)

- Con Forge: activa Let's Encrypt en un clic.
- Manual: `certbot --nginx -d tudominio.co`.

El middleware `SecurityHeaders` ya añade HSTS automáticamente cuando la petición es HTTPS.

## 6. Cron del Scheduler (¡importante!)

Sin esto NO se expiran las citas no pagadas ni se envían recordatorios. Agrega al crontab del servidor:

```cron
* * * * * cd /var/www/health && php artisan schedule:run >> /dev/null 2>&1
```

Esto dispara automáticamente: `appointments:expire` (cada min), `appointments:remind` (cada 15 min), `appointments:complete` (cada hora).

## 7. Worker de colas (correos)

Los correos se envían en segundo plano. Mantén un worker vivo con **Supervisor**:

```ini
[program:health-worker]
command=php /var/www/health/artisan queue:work --sleep=3 --tries=3 --max-time=3600
directory=/var/www/health
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/health/storage/logs/worker.log
```

(Con Forge: sección "Queue", comando `php artisan queue:work`.)

## 8. Mercado Pago en producción

1. En el panel de Mercado Pago, pasa a **credenciales de producción** y ponlas en el `.env`.
2. Configura la **URL del webhook** pública: `https://tudominio.co/webhooks/mercadopago` (tipo de evento: *payments*).
3. Haz una **compra real de bajo monto** y verifica que la cita pase a *Confirmada* por el webhook.

> Para probar el webhook en local antes de producción, expón tu `php artisan serve` con **ngrok** y usa esa URL temporal como `notification_url`.

## 9. Privacidad y backups (datos de salud)

- Backups automáticos diarios de la base de datos (Forge/proveedor o `mysqldump` por cron).
- Revisa que `/terminos` y `/privacidad` reflejen tu razón social y correo de contacto reales.
- Rota cualquier credencial que se haya usado en desarrollo.

## 10. Checklist final (smoke test en producción)

- [ ] La web abre por HTTPS y se instala como app (PWA) en el celular.
- [ ] Registro → login funciona.
- [ ] Agendar → pagar (real) → cita **Confirmada** por webhook.
- [ ] Llega el correo de confirmación.
- [ ] El especialista entra a su panel y pega el enlace de la videollamada.
- [ ] El admin ve citas y pagos.
- [ ] Cron y worker activos (probar que una cita sin pagar expira).
- [ ] Backups configurados.

Cuando todo esto pase: **estás en vivo. 🚀**
