FROM phpswoole/swoole:4.7-php8.0-alpine

# Optional, force UTC as server time
RUN echo "UTC" > /etc/timezone

RUN docker-php-ext-install opcache && docker-php-ext-enable opcache
RUN docker-php-ext-install pcntl
RUN apk add --no-cache libzip-dev && docker-php-ext-configure zip && docker-php-ext-install zip
RUN apk add --no-cache git

RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

WORKDIR /app

ARG CACHEBUST=1
RUN git clone https://github.com/butschster/ray-server.git /app
RUN git pull
RUN composer install

RUN chmod 0777 storage -R

RUN cp /app/.env.example /app/.env
RUN cat /app/.env.example

EXPOSE 8000
CMD php artisan server:start --host=0.0.0.0
