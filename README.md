![Ray server](https://user-images.githubusercontent.com/773481/129159856-a90e2a60-0ca8-4d6b-b80c-2707d9770c3a.png)

# Debug your application with a Ray server

This server application will help you debuging your application with a [Ray tool](https://github.com/spatie/ray)

[Ray](https://myray.app/) is a beautiful, lightweight desktop app that helps you debug your app.

### Built on Laravel

- Laravel 8
- Inertia
- Swoole Websocket
- Ocatne Http server
- Vue
- TailwindCSS
-

## Server requirements

1. PHP 8.0
2. Swoole 4.7

![Ray server devices (1)](https://user-images.githubusercontent.com/773481/129255325-bf91a694-8890-415c-bab4-c86a332986b8.png)

## Docker image
You can run a Ray server via docker

```
docker run -p 8000:8000 -p 23517:23517 butschster/ray-server
```

6. Configure your .env for Ray package
    - `RAY_HOST=127.0.0.1` - Octane server HTTP host
    - `RAY_PORT=8000` - Octane server HTTP port

## Usage

1. Clone repository
2. Run composer `composer install`
3. Setting env variables
    - `WS_SERVER_HOST=127.0.0.1`
    - `WS_SERVER_PORT=23517`
4. Run artisan command `php artisan websocket:serve`
5. Run artisan command `php artisan octane:start --host=127.0.0.1 --port=8000`
6. Configure your .env for Ray package
    - `RAY_HOST=127.0.0.1` - Octane server HTTP host
    - `RAY_PORT=8000` - Octane server HTTP port

Enjoy!

### Build docker image
```
docker build - < Dockerfile -t ray-server --build-arg CACHEBUST=$(date +%s)
```

### License

The Ray server is open-sourced software licensed under the MIT license.

### Tasks to do

- [x] Dark theme
- [x] Simple data
- [x] Colors
- [x] Sizes
- [x] Labels
- [x] New screen
- [x] Clear all
- [x] Caller
- [x] Trace
- [ ] Pause
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
