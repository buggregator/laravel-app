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
RUN git clone https://github.com/butschster/ray-server.git /app
RUN git pull
RUN composer install

RUN echo $'[supervisord] \n\
logfile=/dev/null \n\
nodaemon=true \n\
\n\
[program:octane] \n\
directory=/app \n\
redirect_stderr=true \n\
stdout_logfile=/dev/stdout \n\
stdout_logfile_maxbytes=0 \n\
command=php artisan octane:start --host=0.0.0.0 \n\
\n\
[program:websocket] \n\
directory=/app \n\
redirect_stderr=true \n\
stdout_logfile=/dev/stdout \n\
stdout_logfile_maxbytes=0 \n\
command=php artisan websocket:serve --host=0.0.0.0 ' > /app/supervisord.conf

RUN chmod 0777 storage -R

ENV OCTANE_SERVER=swoole
ENV WS_SERVER_HOST=127.0.0.1
ENV WS_SERVER_PORT=23517
ENV APP_KEY=base64:VuqX8AFwyBraeI0glMgUIV8HTJ5kho6D7G348Vkjg6w=
ENV LOG_CHANNEL=stderr
ENV SESSION_DRIVER=array
ENV APP_DEBUG=false
ENV APP_ENV=production
ENV APP_NAME="Ray server"

RUN cp /app/.env.example /app/.env
RUN cat /app/.env.example

EXPOSE 8000
EXPOSE 23517

CMD ["/usr/bin/supervisord", "-c", "/app/supervisord.conf"]
