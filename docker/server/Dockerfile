FROM php:8.1-alpine

# Optional, force UTC as server time
RUN echo "UTC" > /etc/timezone

RUN docker-php-ext-install opcache && docker-php-ext-enable opcache
RUN docker-php-ext-install pcntl
RUN apk add --no-cache libzip-dev sqlite-dev git && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip

RUN set -ex && apk --no-cache add postgresql-dev
RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql sockets

RUN apk add --update mysql mysql-client && rm -f /var/cache/apk/*

ARG ROADRUNNER_CONFIG=.rr.yaml

# These lines moved to the end allow us to rebuild image quickly after only these files were modified.
COPY startup.sh /startup.sh
COPY my.cnf /etc/mysql/my.cnf

RUN chmod +x /startup.sh

RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

WORKDIR /app

EXPOSE 8000
EXPOSE 1025
EXPOSE 9912
EXPOSE 9913

CMD sh -c /startup.sh
