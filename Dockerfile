FROM php:8.1-apache

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 777 /var/www/html/levels

EXPOSE 80