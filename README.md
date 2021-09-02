![Debugger](https://user-images.githubusercontent.com/773481/131818548-39189a7e-355a-4a9c-b783-9ae8ce627d79.png)

# A server for debugging more than just Laravel applications.

[![Support me on Patreon](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Fshieldsio-patreon.vercel.app%2Fapi%3Fusername%3Dbutschster%26type%3Dpatrons&style=flat)](https://patreon.com/butschster)
[![Downloads](https://img.shields.io/docker/pulls/butschster/debugger.svg)](https://hub.docker.com/repository/docker/butschster/debugger)
<!--[![Join to iur telegram](https://img.shields.io/badge/telegram-Join-blue)](https://t.me/rayserver)-->

Debugger is a beautiful, lightweight web server built on Laravel and VueJs that helps debugging your app. It runs without
installation on multiple platforms. 

#### Contents
1. [Features](#features)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Contributing](#contributing)
5. [License](#license)

## Features

### 1. Symfony [VarDumper server](https://symfony.com/doc/current/components/var_dumper.html#the-dump-server)

The `dump()` and `dd()` functions output its contents in the same browser window or console terminal as your own application. Sometimes mixing the real output with the debug output can be confusing. That’s why this the Debugger can be used to collect all the dumped data. Debugger can display dump output in the browser as well as in a terminal (console output).

**Example**
```
VAR_DUMPER_FORMAT=server
VAR_DUMPER_SERVER=127.0.0.1:9912
```

### 2. Fake SMTP server for catching mail

Debugger also is an email testing tool that makes it super easy to install and configure a local email server (Like [MailHog](https://github.com/mailhog/MailHog)). Debugger sets up a fake SMTP server and you can configure your preferred web applications to use RayServer’s SMTP server to send and receive emails. For instance, you can configure a local WordPress site to use Debugger for email deliveries.

**Example**
```
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
```

### 3. Compatible with Sentry

Debugger can be used to receive Sentry reports from your application. Debugger is a lightweight alternative for local development. Just configure Sentry DSN to send data to Debugger. It can display dump output in the browser as well as in a terminal (console output). 

**Simple example** `SENTRY_LARAVEL_DSN=http://sentry@127.0.0.1:23517/1`.

### 4. Compatible with [Monolog](https://github.com/Seldaek/monolog)

Debugger can receive logs from `monolog/monolog` package via `\Monolog\Handler\SlackWebhookHandler` handler.

**Example**
```
LOG_CHANNEL=slack
LOG_SLACK_WEBHOOK_URL=http://127.0.0.1:23517/slack
```

### 5. Spatie [Ray debug tool](https://github.com/spatie/ray)

Debugger is compatible with `spatie/ray` package. The Ray debug tool supports PHP, Ruby, JavaScript, TypeScript, NodeJS, Go and Bash
applications. After installing one of the libraries, you can use the ray function to quickly dump stuff. Any variable(s) that you pass will be sent to the Debugger. Debugger can display dump output in the browser as well as in a terminal (console output). 

**Supported features**: Simple data, Colors, Sizes, Labels, New screen, Clear all, Caller, Trace, Pause, Counter, Class name of an object, Measure,
Json, Xml, Carbon, File, Table, Image, Html, Text, Notifications, Phpinfo, Exception, Show queries, Count queries, Show events,
Show jobs, Show cache, Model, Show views, Markdown, Collections, Env, Response, Request, Ban, Charles, Remove, Hide/Show events,
Application log, Show Http client requests, Mailable

[Reas more](https://spatie.be/docs/ray/v1/introduction) about `spatie/ray` package.

**Example**
```
RAY_HOST=127.0.0.1  # Ray server host
RAY_PORT=23517      # Ray server port
```

> It is a free alternative of The Ray app for those who want to run a server without GUI, cannot afford the paid version or just like open source.
> 
> **But it doesn’t mean you shouldn’t support spatie’s packages!**

### UI

Debugger has a responsive design and a mobile device can be used as an additional screen for viewing event history. Also you can user a termial to collect dump output if you don't want to use a browser.

![Debugger devices](https://user-images.githubusercontent.com/773481/131818515-bc6c154a-4978-4a57-979e-d0f8cc99f09e.png)

### Code samples

Code samples of configured Laravel application ready to send data to Debugger you can find [here](https://github.com/butschster/ray-server-test/tree/master/tests/Feature).

### Articles

- [A server for debugging more than just Laravel applications.](https://butschster.medium.com/server-for-debugging-not-only-laravel-applications-252814e2931).
- [Сервер для дебага Laravel приложений и не только.](https://butschster.medium.com/%D1%81%D0%B5%D1%80%D0%B2%D0%B5%D1%80-%D0%B4%D0%BB%D1%8F-%D0%B4%D0%B5%D0%B1%D0%B0%D0%B3%D0%B0-laravel-%D0%BF%D1%80%D0%B8%D0%BB%D0%BE%D0%B6%D0%B5%D0%BD%D0%B8%D0%B9-%D0%B8-%D0%BD%D0%B5-%D1%82%D0%BE%D0%BB%D1%8C%D0%BA%D0%BE-4fed54667099)


---


### Technological stack

- [Laravel 8](https://laravel.com/)
- [InertiaJs](https://inertiajs.com/)
- [Swoole](https://www.swoole.co.uk/) Http/Websocket/TCP server
- [Vue](https://vuejs.org/)
- [TailwindCSS](https://tailwindcss.com/)

## Installation

### Docker image

You can run Debugger via docker from [Docker Hub](https://hub.docker.com/repository/docker/butschster/debugger) or using the
provided [Dockerfile](https://github.com/butschster/ray-server/blob/master/Dockerfile)

Just run on bash command 
```
docker run --pull always -p 23517:8000 -p 1025:1025 -p 9912:9912 butschster/debugger:latest

# or 

docker run -p 23517:8000 -p 1025:1025 -p 9912:9912 butschster/debugger:v1.15.2
```

### Configuration

1. Configure your .env for VarDumper
```
VAR_DUMPER_FORMAT=server
VAR_DUMPER_SERVER=127.0.0.1:9912
```
2. Configure your .env for mail
```
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
```
3. Configure your .env for Sentry package
```
SENTRY_LARAVEL_DSN=http://sentry@127.0.0.1:23517/1
```
4. Configure your .env for Ray package
```
RAY_HOST=127.0.0.1  # Debugger host
RAY_PORT=23517      # Debugger port
```
5. Configure your .env for monolog logs
```
LOG_CHANNEL=slack
LOG_SLACK_WEBHOOK_URL=http://127.0.0.1:23517/slack
```

That's it. Now you open http://127.0.0.1:23517 url in your browser or terminal and collect dump output from your application.

Enjoy!

## Contributing

### Server requirements

1. PHP 8.0
2. Swoole 4.7
3. NodeJS

### Installation

1. Clone repository
2. Run composer `composer install
3. Run npm `npm i`
4. Run ray server `php artisan server:start --host=127.0.0.1 --port=23517`
5. Run smtp server `php artisan smtp:start --host=127.0.0.1 --port=1025`
6. Run var-dumper server `php artisan dump-server:start --host=127.0.0.1 --port=9912`
7. Build npm `npm run prod`

## License

Debugger is open-sourced software licensed under the MIT license.
