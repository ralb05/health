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

# Datos iniciales: siembra especialidades, especialistas y horarios SOLO si la
# base está vacía. En reinicios posteriores respeta lo que ya tengas (no borra
# ni duplica), y si alguna vez quedara vacía, los vuelve a cargar.
php artisan app:ensure-seed || true

# Sirve la app en el puerto que asigne la plataforma (Railway/Render/Fly => $PORT).
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
