FROM richarvey/nginx-php-fpm:3.1.6

COPY ./environment/nginx/default.conf /etc/nginx/sites-enabled/default.conf

COPY . /var/www/api

RUN chown -R nginx:nginx /var/www/api \
 && chmod -R 775 /var/www/api/storage \
 && chown -R nginx:nginx /var/cache \
 && chown -R nginx:nginx /var/log \
 && touch /var/www/api/database/database.sqlite \
 && chown nginx:nginx /var/www/api/database/database.sqlite \
 && chmod 664 /var/www/api/database/database.sqlite

RUN COMPOSER_ALLOW_SUPERUSER=1 composer setup -d /var/www/api --no-interaction

CMD php-fpm & nginx -g 'daemon off;'