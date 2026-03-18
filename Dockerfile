FROM php:8.1-apache

COPY . /var/www/html/

# Create and fix permissions for all upload directories
RUN mkdir -p /var/www/html/data \
    /var/www/html/levels/level1/uploads \
    /var/www/html/levels/level2/uploads \
    /var/www/html/levels/level3/uploads \
    /var/www/html/levels/level4/uploads \
 && chmod -R 777 /var/www/html/data \
 && chmod -R 777 /var/www/html/levels/level1/uploads \
 && chmod -R 777 /var/www/html/levels/level2/uploads \
 && chmod -R 777 /var/www/html/levels/level3/uploads \
 && chmod -R 777 /var/www/html/levels/level4/uploads \
 && chown -R www-data:www-data /var/www/html

# Copy entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Configure Apache ServerName to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]