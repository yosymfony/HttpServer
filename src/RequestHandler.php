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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * HTTP requests handler
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
class RequestHandler implements RequestHandlerInterface
{
    protected $port = 8080;
    protected $host = '0.0.0.0';
    protected $statusCode = 200;
    protected $content = '';
    protected $headers = [];
    protected $enableHttpFoundation = false;
    protected $handler;
    
    /**
     * Constructor
     * 
     * @param callable $handler Function to handle each request
     */
    public function __construct(callable $handler)
    {
        $this->prepareHandlerFunction($handler);
        $this->headers = $this->getDefaultHeaders();
    }
    
    /**
     * Setup for listen requests
     * 
     * @param int $port
     * @param string $host
     * 
     * @return RequestHandler
     */
    public function listen($port, $host = '0.0.0.0')
    {
        $this->port = $port;
        $this->host = $host;
        
        return $this;
    }
    
    /**
     * Enable HttpFoundation Request from Symfony. The function handler
     * receives a Symfony\Component\HttpFoundation\Request as argument
     * 
     * @return RequestHandler;
     */
    public function enableHttpFoundationRequest()
    {
        $this->enableHttpFoundation = true;
        
        return $this;
    }
    
    /**
     * @{inheritdoc}
     */
    public function getPort()
    {
        return $this->port;
    }
    
    /**
     * @{inheritdoc}
     */
    public function getHost()
    {
        return $this->host;
    }
    
    /**
     * @{inheritdoc}
     */
    public function getHandlerFunction()
    {
        return $this->handler;
    }
    
    protected function prepareHandlerFunction(callable $handler)
    {
        $this->handler = function(React\Http\Request $request, React\Http\Response $response) use ($handler)
        {
            $requestObj = $request;
            
            if($this->enableHttpFoundation)
            {
                $requestObj = Request::create($request->getPath(), $request->getMethod(), $request->getQuery());
            }

            $result = call_user_func_array($handler, [$requestObj]);
            $this->prepareResult($result);
            
            $response->writeHead($this->statusCode, $this->headers);
            $response->end($this->content);
        };
    }
    
    protected function prepareResult($result)
    {
        if($result instanceof Response)
        {
            $this->statusCode = $result->getStatusCode();
            $this->headers->$result->headers->all();
            $this->content = $output->getContent();
            
            return;
        }
        
        if(is_array($result))
        {
            if(isset($result['content']))
            {
                $this->content = $result['content'];
            }
            
            if(isset($result['status_code']))
            {
                $this->statusCode = $result['status_code'];
            }
            
            if(isset($result['headers']) && is_array($result['headers']))
            {
                $this->headers = $result['headers'];
            }
            
            return;
        }
        
        $this->content = $result;
    }

    protected function getDefaultHeaders()
    {
        return ['Content-Type' => 'text/plain'];
    }
}