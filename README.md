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
- 
## Server requirements
1. PHP 8.0
2. Swoole

## Usage
1. Clone repository
2. Run composer `composer install`
3. Setting env variables
   - `WS_SERVER_HOST=127.0.0.1`
   - `WS_SERVER_PORT=23517`
4. Run artisan command `php artisan websocket:serve`
5. Run artisan command `php artisan octane:start --host=127.0.0.1 --port=8000`
6. Configure your Ray pakcage
   - `RAY_HOST=127.0.0.1` - Octane server HTTP host
   - `RAY_PORT=8000` - Octane server HTTP port


## Docker image
You can run a Ray server via docker

```
docker build - < Dockerfile -t ray-server --build-arg CACHEBUST=$(date +%s)
docker run -p 8000:8000 -p 23517:23517 ray-server
```

Enjoy!

### License
The Ray server is open-sourced software licensed under the MIT license.

### Tasks to do

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
- [ ] Class name of an object
- [ ] Measure
- [ ] Json
- [ ] Xml
- [ ] Carbon
- [ ] File
- [ ] Table
- [ ] Image
- [ ] Html
- [ ] Text
- [ ] Notification
- [ ] Phpinfo
- [ ] Exception
- [x] Show queries
- [ ] Count queries
- [x] Show events
- [ ] Show jobs
- [ ] Show cache
- [ ] Show Http client requests
- [ ] Model
- [ ] Mailable
- [ ] Show views
- [ ] Markdown
- [ ] Env
- [ ] Show WordPress errors
