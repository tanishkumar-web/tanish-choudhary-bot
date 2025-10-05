# Use PHP 8.1 Apache image as base
FROM php:8.1-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies required for PHP extensions
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install curl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy all project files
COPY . .

# Set proper permissions for web server
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Make sure logs directory exists and has proper permissions
RUN mkdir -p /var/www/html/logs && \
    chown -R www-data:www-data /var/www/html/logs && \
    chmod -R 755 /var/www/html/logs

# Make sure data directory exists and has proper permissions
RUN mkdir -p /var/www/html/data && \
    chown -R www-data:www-data /var/www/html/data && \
    chmod -R 755 /var/www/html/data

# Expose port 8080
EXPOSE 8080

# Update Apache configuration to listen on port 8080
RUN echo "Listen 8080" > /etc/apache2/ports.conf && \
    sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf

# Start Apache in foreground
CMD ["apache2-foreground"]