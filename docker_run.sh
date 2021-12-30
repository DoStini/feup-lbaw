#!/bin/bash
set -e

cd /var/www; php artisan config:cache; php artisan storage:link
env >> /var/www/.env
php-fpm8.0 -D
nginx -g "daemon off;"
