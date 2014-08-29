<?php

require_once(__DIR__.'/vendor/autoload.php');

/**
 * Simple Hello World HttpKernel.
 *
 * @author Julius Beckmann <github@h4cc.de>
 */
class ExampleHttpKernel implements \Symfony\Component\HttpKernel\HttpKernelInterface
{
    public function handle(\Symfony\Component\HttpFoundation\Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $name = $request->get('name', 'World');

        return new \Symfony\Component\HttpFoundation\Response('Hello '.$name);
    }
}

// Create our kernel.
$httpKernel = new ExampleHttpKernel();
$options = array(
    'host' => '127.0.0.1',
    'port' => 8081,
);

// Wrap it with the RequestHandler.
$handler = new \Yosymfony\HttpServer\HttpKernelRequestHandler($httpKernel, $options);

// Start the server using the RequestHandler.
$server = new \Yosymfony\HttpServer\HttpServer($handler);
$server->start();

// Check the response of the server with for example:
// $ curl -s 'http://127.0.0.1:8081/?name=Julius'
