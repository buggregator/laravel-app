FROM phpswoole/swoole:4.7-php8.0-alpine

# Optional, force UTC as server time
RUN echo "UTC" > /etc/timezone

RUN docker-php-ext-install opcache && docker-php-ext-enable opcache
RUN docker-php-ext-install pcntl
RUN apk add --no-cache libzip-dev && docker-php-ext-configure zip && docker-php-ext-install zip
RUN apk add --no-cache supervisor git

RUN set -ex && apk --no-cache add postgresql-dev
RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS
RUN docker-php-ext-install pdo pdo_pgsql

ARG ROADRUNNER_STABILITY=stable
ARG ROADRUNNER_VERSION=2.*
ARG ROADRUNNER_CONFIG=.rr.yaml

WORKDIR /app

RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

# Download latest stable version of RoadRunner
RUN vendor/bin/rr get-binary -o linux -a amd64 -f ${ROADRUNNER_VERSION} -s ${ROADRUNNER_STABILITY}

EXPOSE 8000
EXPOSE 1025
EXPOSE 9912
EXPOSE 9913

CMD composer install && php artisan migrate:fresh --force && ./rr serve -c ${ROADRUNNER_CONFIG}
