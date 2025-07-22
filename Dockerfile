# Dockerfile

FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    zip \
    git \
    nginx \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Copie la conf NGINX
COPY ./docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

# Copie la conf supervisor
COPY ./docker/supervisord.conf /etc/supervisord.conf

RUN chmod -R 755 /var/www && chown -R www-data:www-data /var/www


CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
