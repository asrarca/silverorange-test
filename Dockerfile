FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

RUN a2enmod rewrite

# Set the Apache document root to /var/www/html/src
RUN sed -ri -e 's!/var/www/html!/var/www/html/src!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!//var/www/html/src!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
