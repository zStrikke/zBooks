# syntax=docker/dockerfile:1
FROM php:8.4-fpm-bookworm AS base

# Instalador oficial de extensiones de PHP (resuelve dependencias del sistema)
COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
        pdo_pgsql pgsql redis intl gd zip bcmath opcache pcntl exif \
    && apt-get update && apt-get install -y --no-install-recommends git unzip postgresql-client \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Usuario sin privilegios con el mismo UID/GID que el del host (evita ficheros de root en el repo)
ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} zbooks && useradd -m -u ${UID} -g ${GID} -s /bin/bash zbooks

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-zbooks.ini

WORKDIR /var/www/html
USER zbooks
