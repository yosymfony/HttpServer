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
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Yosymfony\HttpServer\HttpKernelRequestHandler;

/**
 * Class HttpKernelRequestHandlerTest.
 *
 * @author Julius Beckmann <github@h4cc.de>
 * @covers Yosymfony\HttpServer\HttpKernelRequestHandler
 */
class HttpKernelRequestHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    private $httpKernelMock;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    private $requestHandler;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    private $handlerFunction;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    private $reactResponseMock;

    public function setUp()
    {
        $this->httpKernelMock = $this->getMockBuilder('\Symfony\Component\HttpKernel\HttpKernelInterface')
            ->setMethods(array('handle'))
            ->getMockForAbstractClass();

        $this->requestHandler = new HttpKernelRequestHandler($this->httpKernelMock, array(
            'port' => 1337,
            'host' => 'foo.bar',
        ));

        $this->handlerFunction = $this->requestHandler->getHandlerFunction();

        $this->reactResponseMock = $this->getMockBuilder('\React\Http\Response')
            ->disableOriginalConstructor()
            ->setMethods(['writeHead', 'end'])
            ->getMock();
    }

    public function testSimpleRequest()
    {
        $response = new SymfonyResponse('some_content');
        $this->httpKernelMock->expects($this->once())->method('handle')
            ->with($this->isInstanceOf('\Symfony\Component\HttpFoundation\Request'))
            ->will($this->returnValue($response));

        $this->reactResponseMock->expects($this->once())->method('writeHead')
            ->with($response->getStatusCode(), $response->headers->all());
        $this->reactResponseMock->expects($this->once())->method('end')
            ->with($response->getContent());

        $request = new Request('GET', '/', ['foo' => 'bar']);

        call_user_func_array($this->handlerFunction, [$request, $this->reactResponseMock]);
    }

    public function testDefaultOptions()
    {
        $this->assertEquals(
            [
                'port' => 8080,
                'host' => '0.0.0.0',
            ],
            $this->requestHandler->getDefaultOptions()
        );
    }

    public function testHostAndPort()
    {
        $this->assertEquals(
            [
                1337,
                'foo.bar',
            ],
            [
                $this->requestHandler->getPort(),
                $this->requestHandler->getHost(),
            ]
        );
    }
}
 