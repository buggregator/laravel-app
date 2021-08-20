![Ray server (3)](https://user-images.githubusercontent.com/773481/130154416-cc7d84e7-086e-4f35-b136-9a9f5ee55974.png)


# Debug your projects with Ray server

[![Support me on Patreon](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Fshieldsio-patreon.vercel.app%2Fapi%3Fusername%3Dbutschster%26type%3Dpatrons&style=flat)](https://patreon.com/butschster)
[![Downloads](https://img.shields.io/docker/pulls/butschster/ray-server.svg)](https://hub.docker.com/repository/docker/butschster/ray-server)

Ray server is a beautiful, lightweight web server built on Laravel that helps debugging your app. It runs without
installation on multiple platforms.

Ray server will help you to debug your projects with a [Ray debug tool](https://github.com/spatie/ray)
from [spatie](https://spatie.be/). Ray debug tool supports PHP, Ruby, JavaScript, TypeScript, NodeJS, Go and Bash
applications. After installing one of the libraries to send information to Ray, you can use the ray function to quickly
dump stuff. Any variable(s) that you pass to ray will be
displayed. [Read more](https://spatie.be/docs/ray/v1/introduction)

### Features

- Compatible with `spatie/ray` package
- Compatible with Sentry (See https://docs.sentry.io/platforms)
- Compatible with Monolog via `\Monolog\Handler\SlackWebhookHandler`

### Technological stack

- Laravel 8
- Inertia
- Swoole Http/Websocket server
- Vue
- TailwindCSS

![Ray server devices (4)](https://user-images.githubusercontent.com/773481/130154428-ad856cff-6d2e-423c-afa8-43ce579ef490.png)


## Docker image

You can run Ray server via docker

Run it from [Docker Hub](https://hub.docker.com/repository/docker/butschster/ray-server) or using the
provided [Dockerfile](https://github.com/butschster/ray-server/blob/master/Dockerfile)

```
docker run --pull always -p 23517:8000 butschster/ray-server:latest

# or 

docker run -p 23517:8000 butschster/ray-server:v1.10
```

### Configuration

1. Install `spatie/ray` or other Ray debug tool in your project
2. Configure your .env for Ray package
    - `RAY_HOST=127.0.0.1` - Ray server host
    - `RAY_PORT=23517` - Ray server port
4. Configure your .env for Sentry package
    - `SENTRY_LARAVEL_DSN=http://sentry@127.0.0.1:23517/1` - Sentry DSN
5. Configure your .env for monolog logs
    - `LOG_CHANNEL=slack`
    - `LOG_SLACK_WEBHOOK_URL=http://127.0.0.1:23517/slack`
6. Open http://127.0.0.1:23517 url in your browser

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
- [ ] Show WordPress errors
- [ ] Dark theme
