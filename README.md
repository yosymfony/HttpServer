A simple HTTP server for PHP
----------------------------

HttpServer is a simple HTTP server powerd [REACT](http://reactphp.org/).

## Installation

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
If you want to receive a [Symfony HttpFoundation Request](http://symfony.com/doc/current/components/http_foundation/introduction.html#request)
you need active this mode:

```
$requestHandler = new RequestHandler( function($request) {
    return 'Hi Yo! Symfony';
});

$requestHandler
    ->listen(8081, '127.0.0.1')
    ->enableHttpFoundationRequest(); // $requestHandler uses fluent interface
```

## The response

The most simple use-case is return a string. By default the `Content-Type` value is `text/plain` at the response header:

```
$requestHandler = new RequestHandler( function($request) {
    return 'Hi Yo! Symfony';
});

```

If you want customize the status code and the response header you can return a array like this:

```
requestHandler = new RequestHandler( function($request) {
    return [
        'content' => '<?xml version="1.0" encoding="UTF-8"?><root>Hi Yo! Symfony</root>',
        'headers' => ['Content-Type' => 'text/xml'],
        'status_code' => 200
    ];
});

```

The best way to make a response is using [Response from Symfony HttpFoundation](http://symfony.com/doc/current/components/http_foundation/introduction.html#response):

```
use Symfony\Component\HttpFoundation\Response;

requestHandler = new RequestHandler( function($request) {
    return new Response(
        'Hi Yo! Symfony',
        Response::HTTP_OK,
        array('content-type' => 'text/html')
    );
});

```

## Unit tests

You can run the unit tests with the following command:

    $ cd your-path/vendor/yosymfony/httpserver
    $ composer.phar install --dev
    $ phpunit
