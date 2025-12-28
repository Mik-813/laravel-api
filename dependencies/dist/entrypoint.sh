#!/bin/sh
set -e

cd /workspace/dependencies

exec nix develop ./shared --command php-fpm -F -y ./dist/php-fpm.conf