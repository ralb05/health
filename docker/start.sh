#!/usr/bin/env bash
#
# Arranque del contenedor en producción.
# Prepara la base SQLite, cachea config/rutas/vistas, corre migraciones y sirve.
set -e

cd /app

# Asegura que exista el archivo SQLite (si usas volumen persistente, vivirá ahí).
mkdir -p database
touch database/database.sqlite

# Limpia y regenera cachés con las variables de entorno ya inyectadas.
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migra la base (idempotente). Quita --force si no quieres correr en producción.
php artisan migrate --force --no-interaction

# Si defines ADMIN_EMAIL en las variables de Railway, ese usuario (ya registrado)
# queda como administrador en cada arranque. Útil para el primer acceso al panel.
if [ -n "${ADMIN_EMAIL}" ]; then
  php artisan app:make-admin "${ADMIN_EMAIL}" || true
fi

# Si defines SEED_ON_BOOT=true, carga datos de ejemplo (especialidades + doctores
# + horarios). Es idempotente (updateOrCreate). Útil para arrancar rápido; luego
# gestiona tus propios datos desde el panel y pon SEED_ON_BOOT en false.
if [ "${SEED_ON_BOOT}" = "true" ]; then
  php artisan db:seed --class=CatalogSeeder --force --no-interaction || true
  php artisan db:seed --class=ScheduleSeeder --force --no-interaction || true
fi

# Sirve la app en el puerto que asigne la plataforma (Railway/Render/Fly => $PORT).
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
