FROM phpswoole/swoole:4.7-php8.0-alpine

# Optional, force UTC as server time
RUN echo "UTC" > /etc/timezone

RUN docker-php-ext-install opcache && docker-php-ext-enable opcache
RUN docker-php-ext-install pcntl
RUN apk add --no-cache libzip-dev && docker-php-ext-configure zip && docker-php-ext-install zip
RUN apk add --no-cache supervisor git

WORKDIR /app

ENV OCTANE_SERVER=swoole
ENV WS_SERVER_HOST=127.0.0.1
ENV WS_SERVER_PORT=23518
ENV APP_KEY=base64:VuqX8AFwyBraeI0glMgUIV8HTJ5kho6D7G348Vkjg6w=
ENV LOG_CHANNEL=stderr
ENV SESSION_DRIVER=array
ENV APP_NAME="Ray server"

EXPOSE 8000
EXPOSE 23518

CMD ["/usr/bin/supervisord", "-c", "/app/supervisord.conf"]
