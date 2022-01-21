FROM php:7.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends apt-utils\
    git \
    curl \
    nano

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pcntl bcmath

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

USER www-data:www-data
