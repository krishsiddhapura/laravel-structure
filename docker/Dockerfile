# PULLING PHP-FPM IMAGE FROM DOCKER HUB AND USING IT AS php
# NOTE : THE IMAGE IS BASED ON Debian IMAGE SO Debian (LINUX ENVIORNMENT) WILL AUTMATICALLY BE INSTALLED WITH PHP
FROM php:8.2-fpm as php

# RUNNING ESSENCIAL COMMANDS AND INSTALLING REQUIRED SERVER PACKAGES
RUN apt-get update && apt-get install -y \
    nginx \
    gzip \
    libzip-dev \
    git \
    netcat-openbsd \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo pdo_mysql zip gd \
    && apt-get -y clean \
    && rm -rf /var/lib/apt/lists/*

# INSTALLING COMPOSER
COPY --from=composer:2.8.3 /usr/bin/composer /usr/bin/composer

# REPLACING ALL THE CONFIG FILES TO SERVER FOLDERS
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

# SETUP WORKING DIRECTORY TO MAKE SURE ALL COMMANDS ARE BEING EXECUTED INSIDE RELATIVE DIRECTORY
WORKDIR /var/www/html

# COPIYING PROJECT FILES AND .env FILE INTO ROOT FOLDER
COPY . /var/www/html
COPY ./docker/php-src/.env /var/www/html/

# GIVING PERMISSIONS TO FOLDERS
RUN mkdir -p /var/lib/nginx/body && chown -R www-data:www-data /var/lib/nginx/ && chmod -R 775 /var/lib/nginx
RUN mkdir -p /var/log/nginx && chown -R www-data:www-data /var/log/nginx
RUN chown -R www-data:www-data /var/www/html/ && chmod -R 775 /var/www/html/

# INSTLALLING LARAVEL DEPENDENCIES
RUN composer install --no-dev --optimize-autoloader

# GIIVING EXECUTION PERMISSION TO .sh FILE FOR FURTHER USE
RUN chmod +x /var/www/html/docker/entrypoint_fpm.sh

# SWITCH TO NON-ROOT USER
USER www-data

# SETS OF COMMANDS WHICH WILL BE EXECUTED ONCE INSTALLATION IS COMPLETED AND CONTAINR STARTS
ENTRYPOINT ["/var/www/html/docker/entrypoint_fpm.sh"]
