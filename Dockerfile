# ---- Etapa de build: dependencias de Composer ----
FROM composer:2 AS build
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

# ---- Etapa de runtime: Nginx + PHP-FPM ----
FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx gettext \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS libpng-dev libjpeg-turbo-dev freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo_mysql \
    && apk del .build-deps

COPY --from=build /app/vendor /app/vendor
COPY . /app
COPY nginx.conf /tmp/nginx.conf
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8080
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]