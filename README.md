![Ray server](https://user-images.githubusercontent.com/773481/129159856-a90e2a60-0ca8-4d6b-b80c-2707d9770c3a.png)

# Debug your projects with Ray server

[![Support me on Patreon](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Fshieldsio-patreon.vercel.app%2Fapi%3Fusername%3Dbutschster%26type%3Dpatrons&style=flat)](https://patreon.com/butschster)

This Ray server will help you debuging your projects with a [Ray tool](https://github.com/spatie/ray)

### Technological stack

- Laravel 8
- Inertia
- Swoole Websocket
- Ocatne Http server
- Vue
- TailwindCSS

## Server requirements

1. PHP 8.0
2. Swoole 4.7

![Ray server devices (1)](https://user-images.githubusercontent.com/773481/129255325-bf91a694-8890-415c-bab4-c86a332986b8.png)

## Docker image
You can run a Ray server via docker

```
docker run -p 23517:8000 butschster/ray-server:v0.10
```

1. Install `spatie/ray` or other in your project
2. Configure your .env for Ray package
    - `RAY_HOST=127.0.0.1` - Octane server HTTP host
    - `RAY_PORT=23517` - Octane server HTTP port
3. Open http://127.0.0.1:23517 url in your browser

## Usage

1. Clone repository
2. Run composer `composer install`
4. Run artisan command `php artisan server:start --host=127.0.0.1 --port=23517`
6. Install `spatie/ray` or other in your project
7. Configure your .env for Ray package
    - `RAY_HOST=127.0.0.1` - Octane server HTTP host
    - `RAY_PORT=23517` - Octane server HTTP port
8. Open http://127.0.0.1:23517 url in your browser

Enjoy!

### Build docker image
```
docker build - < Dockerfile -t butschster/ray-server --build-arg CACHEBUST=$(date +%s)
docker push butschster/ray-server:latest
docker tag butschster/ray-server:latest butschster/ray-server:vX.X
docker push butschster/ray-server:vX.X
```

### License

The Ray server is open-sourced software licensed under the MIT license.

### Tasks to do

- [ ] Dark theme
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
- [ ] Show Http client requests
- [x] Model
- [ ] Mailable
- [x] Show views
- [x] Markdown
- [x] Collections
- [x] Env
- [x] Response
- [x] Request
- [ ] Show WordPress errors
- [x] Ban
- [x] Charles
- [x] Remove
