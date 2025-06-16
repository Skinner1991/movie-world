FROM php:8.2-apache

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    curl \
    && docker-php-ext-install pdo pdo_mysql zip

# Set working directory
WORKDIR /var/www/html

# Copy composer files first to cache composer layers
COPY composer.json composer.lock* ./

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/bin/composer

# Install PHP dependencies
RUN composer install

# Now copy the rest of the application
COPY . .

# Use public/ as the DocumentRoot
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
