#!/bin/sh

set -e

chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/database

nginx -g 'daemon off;'