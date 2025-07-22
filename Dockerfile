# Dockerfile (à la racine du projet)
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    zip \
    git \
    nginx \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Configuration NGINX
COPY ./docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

CMD service nginx start && php-fpm
