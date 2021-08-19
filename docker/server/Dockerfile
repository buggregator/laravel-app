FROM phpswoole/swoole:4.7-php8.0-alpine

# Optional, force UTC as server time
RUN echo "UTC" > /etc/timezone

RUN docker-php-ext-install opcache && docker-php-ext-enable opcache
RUN docker-php-ext-install pcntl
RUN apk add --no-cache libzip-dev && docker-php-ext-configure zip && docker-php-ext-install zip
RUN apk add --no-cache git

RUN set -ex && apk --no-cache add postgresql-dev
RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS
RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /app

EXPOSE 8000
CMD php artisan migrate:fresh && php artisan server:start --host=0.0.0.0
