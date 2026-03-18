#!/bin/bash
set -e

# Fix data directory permissions
mkdir -p /var/www/html/data
chmod 777 /var/www/html/data

# Fix all level upload directories permissions
for level in 1 2 3 4; do
    mkdir -p /var/www/html/levels/level${level}/uploads
    chmod 777 /var/www/html/levels/level${level}/uploads
done

# Start Apache
exec apache2-foreground
