# syntax=docker/dockerfile:1
#
# Imagen de producción para Mind & Health (Laravel 13 + Vite/Tailwind v4).
# Multi-stage: 1) compila los assets con Node, 2) runtime PHP que sirve la app.
# Sirve en el puerto que inyecte la plataforma vía la variable de entorno PORT.

# ---- Etapa 1: build de assets (Vite/Tailwind) ----
FROM node:20-bookworm-slim AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---- Etapa 2: runtime PHP ----
# 8.5: requerido por mercadopago/dx-php (>=8.5) y symfony 8.1 (>=8.4.1) según composer.lock.
FROM php:8.5-cli-bookworm AS app

# Dependencias de sistema + extensiones PHP que necesita Laravel y MercadoPago.
# Incluye drivers de SQLite, MySQL y PostgreSQL para que nunca falte "driver".
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libzip-dev libicu-dev libsqlite3-dev libcurl4-openssl-dev libpq-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql pdo_pgsql zip intl curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer (copiado desde la imagen oficial).
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Instala dependencias PHP primero para aprovechar la caché de capas.
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist --optimize-autoloader

# Copia el código de la app y los assets ya compilados de la etapa anterior.
COPY . .
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize --no-dev \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache

COPY docker/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start

EXPOSE 8080
CMD ["start"]
