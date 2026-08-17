FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
        nginx \
        supervisor \
        sqlite \
        curl \
    && docker-php-ext-install pdo pdo_sqlite

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --prefer-dist \
    --ignore-platform-req=ext-zip --ignore-platform-req=ext-intl

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

COPY docker/supervisord.conf /etc/supervisord.conf

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]