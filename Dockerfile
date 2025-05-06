FROM php:7.4-apache

# Set working directory
WORKDIR /var/www/html

# Install needed extensions
RUN docker-php-ext-install mysqli

# Copy website files into the container
COPY public/ /var/www/html/

# Fix permissions and ownership
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Optional: Enable Apache rewrite module
RUN a2enmod rewrite

# Expose Apache default port
EXPOSE 80

# Healthcheck
CMD ["sh", "-c", "php /var/www/html/healthcheck.php && apache2-foreground"]