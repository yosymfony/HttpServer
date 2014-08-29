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

    protected function prepareHandlerFunction()
    {
        $this->handlerFunction = function(ReactRequest $reactRequest, ReactResponse $reactResponse)
        {
            // Create symfony request and response.
            $symfonyRequest = SymfonyRequest::create(
                $reactRequest->getPath(),
                $reactRequest->getMethod(),
                $reactRequest->getQuery()
            );
            $symfonyResponse = $this->httpKernel->handle($symfonyRequest);

            // Give response to React.
            $reactResponse->writeHead($symfonyResponse->getStatusCode(), $symfonyResponse->headers->all());
            $reactResponse->end($symfonyResponse->getContent());

            // Trigger HttpKernel terminate event.
            if($this->httpKernel instanceof TerminableInterface) {
                $this->httpKernel->terminate($symfonyRequest, $symfonyResponse);
            }
        };
    }
}