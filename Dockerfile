FROM php:8.1-apache

COPY . /var/www/html/

# Create data directory and set permissions
RUN mkdir -p /var/www/html/data \
 && chown -R www-data:www-data /var/www/html \
 && chmod -R 777 /var/www/html

EXPOSE 80