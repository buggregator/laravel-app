![Ray server](https://user-images.githubusercontent.com/773481/131818548-39189a7e-355a-4a9c-b783-9ae8ce627d79.png)


# A server for debugging more than just Laravel applications.

[![Support me on Patreon](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Fshieldsio-patreon.vercel.app%2Fapi%3Fusername%3Dbutschster%26type%3Dpatrons&style=flat)](https://patreon.com/butschster)
[![Downloads](https://img.shields.io/docker/pulls/butschster/ray-server.svg)](https://hub.docker.com/repository/docker/butschster/ray-server)
[![Join to iur telegram](https://img.shields.io/badge/telegram-Join-blue)](https://t.me/rayserver)

RayServer is a beautiful, lightweight web server built on Laravel and VueJs that helps debugging your app. It runs without
installation on multiple platforms. 

RayServer will help you debugging your projects with the [Ray debug tool](https://github.com/spatie/ray)
from [spatie](https://spatie.be/). The Ray debug tool supports PHP, Ruby, JavaScript, TypeScript, NodeJS, Go and Bash
applications. After installing one of the libraries to send information to Ray, you can use the ray function to quickly
dump stuff. Any variable(s) that you pass to ray will be displayed. [Reas more](https://spatie.be/docs/ray/v1/introduction). 

RayServer also is an email testing tool that makes it super easy to install and configure a local email server. RayServer sets up a fake SMTP server and you can configure your preferred web applications to use RayServer’s SMTP server to send and receive emails. For instance, you can configure a local WordPress site to use RayServer for email deliveries.

Moreover RayServer is compatible with sentry, monolog. 

> It is a free alternative of The Ray app for those who want to run a server without GUI, cannot afford the paid version or just like open source.
> 
> **But it doesn’t mean you shouldn’t support spatie’s packages!**


You can find out more information about the RayServer [here](https://butschster.medium.com/server-for-debugging-not-only-laravel-applications-252814e2931).


### Features

- Compatible with `symfony/var-dumper` package. RayServer can be used as a [dump server](https://symfony.com/doc/current/components/var_dumper.html#the-dump-server).
- Compatible with `spatie/ray` package.
- Compatible with Sentry (See https://docs.sentry.io/platforms)
- Compatible with Monolog via `\Monolog\Handler\SlackWebhookHandler`
- Compatible with SMTP. RayServer can be used as an email testing tool

Code samples of usage you can find [here](https://github.com/butschster/ray-server-test/tree/master/tests/Feature)


### Technological stack

- Laravel 8
- Inertia
- Swoole Http/Websocket/TCP server
- Vue
- TailwindCSS

![Ray server devices](https://user-images.githubusercontent.com/773481/131818515-bc6c154a-4978-4a57-979e-d0f8cc99f09e.png)
![Ray server settings (4)](https://user-images.githubusercontent.com/773481/131818546-85496772-f799-4172-ae2c-9ca6c39b11a8.png)


Code samples of usage you can find [here](https://github.com/butschster/ray-server-test/tree/master/tests/Feature
## Docker image

You can run Ray server via docker

Run it from [Docker Hub](https://hub.docker.com/repository/docker/butschster/ray-server) or using the
provided [Dockerfile](https://github.com/butschster/ray-server/blob/master/Dockerfile)

```
docker run --pull always -p 23517:8000 -p 1025:1025 -p 9912:9912 butschster/ray-server:latest

# or 

docker run -p 23517:8000 -p 1025:1025 -p 9912:9912 butschster/ray-server:v1.12
```

### Configuration

1. Configure your .env for Ray package
    - `RAY_HOST=127.0.0.1` - Ray server host
    - `RAY_PORT=23517` - Ray server port
2. Configure your .env for Sentry package
    - `SENTRY_LARAVEL_DSN=http://sentry@127.0.0.1:23517/1` - Sentry DSN
3. Configure your .env for monolog logs
    - `LOG_CHANNEL=slack`
    - `LOG_SLACK_WEBHOOK_URL=http://127.0.0.1:23517/slack`
4. Configure your .env for mail
    - `MAIL_MAILER=smtp`
    - `MAIL_HOST=127.0.0.1`
    - `MAIL_PORT=1025`
5. Configure your .env for VarDumper
    - `VAR_DUMPER_FORMAT=server`
    - `VAR_DUMPER_SERVER=127.0.0.1:9912`
5. Open http://127.0.0.1:23517 url in your browser

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
4. Run smtp server `php artisan smtp:start --host=127.0.0.1 --port=1025`
5. Build npm `npm run prod`

## License

Ray server is open-sourced software licensed under the MIT license.

### Tasks to do

- [x] Simple data
- [x] Colors
- [x] Sizes
- [x] Labels
- [x] New screen
- [x] Clear all
- [x] Caller
- [x] Trace
- [x] Pause
- [x] Counter
- [x] Class name of an object
- [x] Measure
- [x] Json
- [x] Xml
- [x] Carbon
- [x] File
- [x] Table
- [x] Image
- [x] Html
- [x] Text
- [x] Notifications
- [x] Phpinfo
- [x] Exception
- [x] Show queries
- [x] Count queries
- [x] Show events
- [x] Show jobs
- [x] Show cache
- [x] Model
- [x] Show views
- [x] Markdown
- [x] Collections
- [x] Env
- [x] Response
- [x] Request
- [x] Ban
- [x] Charles
- [x] Remove
- [x] Hide/Show events
- [x] Sticky header
- [x] Application log
- [x] Show Http client requests
- [x] Mailable
- [x] Sentry
- [x] Monolog
- [x] SMTP server
- [x] Symfony var-dumper server
- [ ] Show WordPress errors
- [ ] Dark theme
