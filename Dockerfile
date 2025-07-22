FROM php:8.3-fpm

# Installer nginx, supervisor et extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    libpq-dev \
    libzip-dev \
    zip unzip \
    git curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Répertoire de travail
WORKDIR /var/www/html

# Copier l’application
COPY . .

# Installer les dépendances PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader || true

# Copier config nginx et supervisor
COPY docker/nginx/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisord.conf

# Exposer le port HTTP
EXPOSE 80

# Lancer Supervisor (gère PHP-FPM + nginx)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
