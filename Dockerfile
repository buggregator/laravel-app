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

RUN git clone https://github.com/butschster/ray-server.git /app
RUN git checkout ${APP_VERSION}
RUN composer install

RUN chmod 0777 storage -R

RUN cp /app/.env.example /app/.env
RUN cat /app/.env.example
RUN echo "APP_VERSION=\"${APP_VERSION}\"" >> /app/.env

RUN cat /app/.env

# Create a sqlite database
RUN touch /app/database/database.sqlite

EXPOSE 8000
EXPOSE 1025
EXPOSE 9912
EXPOSE 9913

CMD php artisan migrate:fresh --force && /usr/bin/supervisord -c /app/supervisord.conf
