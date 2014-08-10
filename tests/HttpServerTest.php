<?php

/*
 * This file is part of the Yosymfony\HttpServer.
 *
 * (c) YoSymfony <http://github.com/yosymfony>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Yosymfony\HttpServer\Tests;

use React\Http\Request;
use React\Http\Response;
use Yosymfony\HttpServer\HttpServer;
use Yosymfony\HttpServer\RequestHandler;

class HttpServerTest extends \PHPUnit_Framework_TestCase
{
    protected $request;
    protected $response;
    
    public function setUp()
    {
        $headers = [];
        $this->request = new Request('GET', '/', array(), '1.1', $headers);
        
        $conn = $this->getMock('React\Socket\ConnectionInterface');
        $this->response = new Response($conn);
    }
    
    public function testHttpServer()
    {
        $requestHandler = new RequestHandler(function($request) {
            
            $this->assertInstanceOf('React\Http\Request', $request);
            
            return 'Hi Yo! Symfony';
        });
        
        $handler = $requestHandler->getHandlerFunction();
        call_user_func_array($handler, [$this->request, $this->response]);
    }
    
    public function testHttpServerWithHttpFoundationRequest()
    {
        $requestHandler = new RequestHandler(function($request) {
            
            $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $request);
            
            return 'Hi Yo! Symfony';
        });
        
        $requestHandler->enableHttpFoundationRequest();
        
        $handler = $requestHandler->getHandlerFunction();
        call_user_func_array($handler, [$this->request, $this->response]);
    }
    
    public function testConfigureHost()
    {
        $requestHandler = new RequestHandler(function($request) {
            return 'Hi Yo! Symfony';
        });
        $requestHandler->listen(8080, '127.0.0.1');
        
        $this->assertEquals(8080, $requestHandler->getPort());
        $this->assertEquals('127.0.0.1', $requestHandler->getHost());
    }
}