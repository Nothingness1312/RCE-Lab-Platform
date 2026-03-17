FROM php:8.1-apache

# Enable SQLite3 extension
RUN docker-php-ext-install sqlite3 \
 && docker-php-ext-enable sqlite3

COPY . /var/www/html/

# Create data directory with full permissions
RUN mkdir -p /var/www/html/data /var/www/html/levels/level1/uploads /var/www/html/levels/level2/uploads \
 && chown -R www-data:www-data /var/www/html \
 && chmod -R 777 /var/www/html

# Configure Apache ServerName to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80