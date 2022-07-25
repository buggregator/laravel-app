FROM php:8.0-alpine

# Optional, force UTC as server time
RUN echo "UTC" > /etc/timezone

RUN docker-php-ext-install opcache && docker-php-ext-enable opcache
RUN docker-php-ext-install pcntl
RUN apk add --no-cache libzip-dev sqlite-dev supervisor git && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip

RUN set -ex && apk --no-cache add postgresql-dev
RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS
RUN docker-php-ext-install pdo pdo_sqlite pdo_pgsql pdo_mysql sockets

ARG ROADRUNNER_CONFIG=.rr.yaml

WORKDIR /app

RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

EXPOSE 8000
EXPOSE 1025
EXPOSE 9912
EXPOSE 9913

CMD composer install && ./rr serve -c ${ROADRUNNER_CONFIG}
