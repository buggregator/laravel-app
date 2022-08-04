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

RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

WORKDIR /app

ARG CACHEBUST=1
ARG APP_VERSION=v1.0

COPY docker/server/startup.sh /startup.sh
COPY docker/server/my.cnf /etc/mysql/my.cnf

RUN git clone https://github.com/buggregator/app.git /app
RUN composer install

RUN chmod 0777 storage -R
RUN chmod 0777 bootstrap -R
RUN chmod +x /startup.sh

# Create .env file
RUN cp .env.example .env
RUN cat .env.example
RUN echo "APP_VERSION=\"${APP_VERSION}\"" >> .env

EXPOSE 8000
EXPOSE 1025
EXPOSE 9912
EXPOSE 9913
EXPOSE 3306

CMD sh -c /startup.sh
