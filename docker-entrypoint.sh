#!/bin/sh
set -e

# Reemplaza ${PORT} (variable del PaaS, ej. Railway/Render) en la config de Nginx
envsubst '${PORT}' < /tmp/nginx.conf > /etc/nginx/nginx.conf

# Inicia PHP-FPM en segundo plano y Nginx en primer plano
php-fpm -D
exec nginx -g 'daemon off;'