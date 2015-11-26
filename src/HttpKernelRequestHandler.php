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

use React\Http\Request as ReactRequest;
use React\Http\Response as ReactResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

/**
 * Request handler for HttpKernel.
 *
 * @author Julius Beckmann <github@h4cc.de>
 */
class HttpKernelRequestHandler implements RequestHandlerInterface
{
    protected $httpKernel;
    protected $handlerFunction;
    protected $options;
    protected $request;
    protected $response;

    function __construct(HttpKernelInterface $httpKernel, array $options = array())
    {
        $this->options = array_merge($this->getDefaultOptions(), $options);

        $this->httpKernel = $httpKernel;
        $this->prepareHandlerFunction();
    }

    public function getDefaultOptions()
    {
        return array(
            'port' => 8080,
            'host' => '0.0.0.0',
        );
    }

    /**
     * @{inheritdoc}
     */
    public function getHandlerFunction()
    {
        return $this->handlerFunction;
    }

    /**
     * @{inheritdoc}
     */
    public function getPort()
    {
        return $this->options['port'];
    }

    /**
     * @{inheritdoc}
     */
    public function getHost()
    {
        return $this->options['host'];
    }

    /**
     * @{inheritdoc}
     */
    public function setRequest($request) {
        $this->request  = $request;
    }

    /**
     * @{inheritdoc}
     */
    public function setResponse($response) {
        $this->response  = $response;
    }
    
    protected function prepareHandlerFunction()
    {
        $this->handlerFunction = function(ReactRequest $reactRequest, ReactResponse $reactResponse)
        {
            if($this->request == null) {
                // Create symfony request and response.
                $this->request = SymfonyRequest::create(
                    $reactRequest->getPath(),
                    $reactRequest->getMethod(),
                    $reactRequest->getQuery()
                );
            }

            if($this->response == null) {
                $this->response = $this->httpKernel->handle($this->request);
            }

            // Give response to React.
            $reactResponse->writeHead($this->response->getStatusCode(), $this->response->headers->all());
            $reactResponse->end($this->response->getContent());

            // Trigger HttpKernel terminate event.
            if($this->httpKernel instanceof TerminableInterface) {
                $this->httpKernel->terminate($this->request, $this->response);
            }
        };
    }
}