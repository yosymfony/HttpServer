<?php

/*
 * This file is part of the Yosymfony\HttpServer.
 *
 * (c) YoSymfony <http://github.com/yosymfony>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Yosymfony\HttpServer;

use React;

/**
 * HTTP Server powered by REACT
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
class HttpServer
{
    private $requestHandler;
    private $loop;
    
    /**
     * Constructor
     * 
     * @param RequestHandlerInterface $requestHandler
     */
    public function __construct(RequestHandlerInterface $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }
    
    /**
     * start the loop
     */
    public function start()
    {
        $this->loop = React\EventLoop\Factory::create();
        $socket = new React\Socket\Server($this->loop);
        
        $http = new React\Http\Server($socket);
        $http->on('request', $function = $this->requestHandler->getHandlerFunction());
        
        $socket->listen($this->requestHandler->getPort(), $this->requestHandler->getHost());
        $this->loop->run();
    }
}