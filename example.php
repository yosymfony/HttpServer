<?php

require_once(__DIR__ . '/vendor/autoload.php');

$requestHandler = new \Yosymfony\HttpServer\RequestHandler(function ($request) {
    return new \Symfony\Component\HttpFoundation\Response(
        'Hi ' . $request->get('name', 'Yo! Symfony'),
        200,
        array('content-type' => 'text/html')
    );
});
$requestHandler->enableHttpFoundationRequest();

$server = new \Yosymfony\HttpServer\HttpServer($requestHandler);
$server->start();

// go to http://localhost:8080