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

# Sirve la app en el puerto que asigne la plataforma (Railway/Render/Fly => $PORT).
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
