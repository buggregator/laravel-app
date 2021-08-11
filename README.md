## Server application for Ray debug tool

This server application will help you debug your application with Ray tool without 

[Ray](https://myray.app/) is a beautiful, lightweight desktop app that helps you debug your app.

# Server requirements
1. PHP 8.0
2. Swoole

# Usage
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

Enjoy!

## License
The Ray server is open-sourced software licensed under the MIT license.
