A simple HTTP server for PHP
----------------------------

HttpServer is a simple HTTP server powerd [REACT](http://reactphp.org/).

Installation
------------

Use [Composer](http://getcomposer.org/) to install Yosyfmony HttpServer package:

Add the following to your `composer.json` and run `composer update`.

    "require": {
        "yosymfony/httpserverl": "1.0.x-dev"
    }

More informations about the package on [Packagist](https://packagist.org/packages/yosymfony/httpserver).

## How to use?

It's simple. The RequestHandler need a function for managing each connection:

```
$requestHandler = new RequestHandler(function($request) {
    return 'Hi Yo! Symfony';
});

$server = new HttpServer($requestHandler);
$server->start();

// go to http://localhost:8080
```

## How to configure the RequestHandler?

**You can configure port and host for listening requests**:

```
$requestHandler = new RequestHandler( function($request) {
    return 'Hi Yo! Symfony';
});

$requestHandler->listen(8081, '127.0.0.1');
```

The defatult values:
* port: 8080
* host: 0.0.0.0

### The handler function

The handler function receives a unique parameter to describe the resquest. By default, this argument
is a object type [React\Http\Request](https://github.com/reactphp/http/blob/master/src/Request.php).
If you want to receive a Symfony HttpFoundation Request you need active this mode:

```
$requestHandler = new RequestHandler( function($request) {
    return 'Hi Yo! Symfony';
});

$requestHandler
    ->listen(8081, '127.0.0.1')
    ->enableHttpFoundationRequest(); // $requestHandler uses fluent interface
```