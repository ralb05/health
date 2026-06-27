#!/usr/bin/env bash
#
# Arranca el servidor de desarrollo de Mind & Health usando el PHP correcto
# (PHP 8.5 de Homebrew), sin importar el PATH del terminal.
#
# Uso:  ./serve.sh            -> http://127.0.0.1:8000
#       ./serve.sh 8080       -> usa otro puerto
#
set -e

PHP=/opt/homebrew/bin/php
PORT="${1:-8000}"

cd "$(dirname "$0")"

echo "Mind & Health — servidor de desarrollo"
echo "PHP: $("$PHP" -r 'echo PHP_VERSION;')"
echo "URL: http://127.0.0.1:${PORT}"
echo "(Ctrl + C para detener)"
echo

exec "$PHP" artisan serve --port="$PORT"
