A simple HTTP server for PHP
____________________________

## How to use?
```
$requestHandler = new $RequestHandler(function($request) {
    return 'Hi Yo! Symfony';
});

$server = new HttpServer($requestHandler);
$server->start();
```