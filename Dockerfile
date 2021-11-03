FROM phpswoole/swoole:4.7-php8.0-alpine

# Optional, force UTC as server time
RUN echo "UTC" > /etc/timezone

RUN docker-php-ext-install opcache && docker-php-ext-enable opcache
RUN docker-php-ext-install pcntl
RUN apk add --no-cache libzip-dev && docker-php-ext-configure zip && docker-php-ext-install zip
RUN apk add --no-cache supervisor git

RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

WORKDIR /app

ARG CACHEBUST=1
ARG APP_VERSION=v1.0
ARG ROADRUNNER_STABILITY=stable
ARG ROADRUNNER_VERSION=2.*
ARG ROADRUNNER_CONFIG=.rr.yaml

RUN git clone https://github.com/buggregator/app.git /app
RUN composer install

RUN chmod 0777 storage -R
RUN chmod 0777 bootstrap -R

# Create .env file
RUN cp /app/.env.example /app/.env
RUN cat /app/.env.example
RUN echo "APP_VERSION=\"${APP_VERSION}\"" >> /app/.env

# Create a sqlite database
RUN touch /app/database/database.sqlite

# Download latest stable version of RoadRunner
RUN vendor/bin/rr get-binary -o linux -a amd64 -f ${ROADRUNNER_VERSION} -s ${ROADRUNNER_STABILITY}

EXPOSE 8000
EXPOSE 1025
EXPOSE 9912
EXPOSE 9913

CMD php artisan migrate:fresh --force && ./rr serve -c ${ROADRUNNER_CONFIG}
