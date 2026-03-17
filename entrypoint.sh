#!/bin/bash
set -e

# Fix data directory permissions
mkdir -p /var/www/html/data
chmod 777 /var/www/html/data

# Start Apache
exec apache2-foreground
