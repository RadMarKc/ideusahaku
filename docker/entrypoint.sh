#!/bin/sh
set -e

if [ -z "$APP_KEY" ]; then
    echo "No APP_KEY found, generating one..."
    php artisan key:generate --force
fi

if [ ! -f database/database.sqlite ]; then
    mkdir -p database
    touch database/database.sqlite
fi

php artisan migrate --force

php artisan db:seed --force

exec supervisord -c /etc/supervisord.conf