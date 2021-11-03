FROM php:8.0-alpine

# Optional, force UTC as server time
RUN echo "UTC" > /etc/timezone

RUN apk add --no-cache git curl sqlite curl-dev
RUN docker-php-ext-configure curl && docker-php-ext-install curl
RUN docker-php-ext-install opcache && docker-php-ext-enable opcache
RUN docker-php-ext-install pcntl
RUN apk add --no-cache libzip-dev && docker-php-ext-configure zip && docker-php-ext-install zip

RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

WORKDIR /app

RUN git clone https://github.com/buggregator/examples.git /app
RUN composer install

RUN chmod 0777 storage -R
RUN chmod 0777 bootstrap -R

# RUN cp /app/.env.example /app/.env


EXPOSE 8000

CMD php artisan serve --host=0.0.0.0
