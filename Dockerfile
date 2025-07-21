# Dockerfile

FROM php:8.3-fpm

# Installer extensions nécessaires
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    libpq-dev \
    libzip-dev \
    zip unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Copier Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier l'app
WORKDIR /var/www/html
COPY . .

# Copier la config nginx
COPY nginx.conf /etc/nginx/sites-available/default

# Copier la config supervisor
COPY supervisord.conf /etc/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord"]
