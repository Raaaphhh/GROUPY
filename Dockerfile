FROM php:8.3-apache

# Installer git, unzip et les libs pour GD
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql \
    && apt-get clean

# Configurer Apache MPM et mod_rewrite
RUN a2dismod mpm_event && a2enmod mpm_prefork && a2enmod rewrite

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le projet
COPY . /var/www/html/

# Installer les dépendances (sans les dev)
WORKDIR /var/www/html
RUN composer install --optimize-autoloader --no-scripts --no-interaction --no-dev

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
```

Le changement clé c'est cette ligne :
```
RUN a2dismod mpm_event && a2enmod mpm_prefork && a2enmod rewrite