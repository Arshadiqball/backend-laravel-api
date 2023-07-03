# Use the official PHP image as the base image
FROM php:8.1-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Install PHP extensions and other dependencies
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        && docker-php-ext-install zip pdo_mysql

# Install MySQL client
RUN apt-get install -y default-mysql-client

# Copy the project files into the container
COPY . /var/www/html

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev

# Set the proper file permissions
RUN chown -R www-data:www-data /var/www/html/storage

# Enable Apache modules and configurations
RUN a2enmod rewrite

# Expose the desired port
EXPOSE 8000

# Start the Apache web server
CMD ["apache2-foreground"]
