#!/bin/sh

set -e

DIST="/workspace/dependencies/dist/php-laravel"

php-fpm -F -R -y $DIST/php-fpm.conf
