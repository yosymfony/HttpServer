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

/**
 * Inteface for Requests handler
 * 
 * @author Victor Puertas <vpgugr@gmail.com>
 */
interface RequestHandlerInterface
{
    /**
     * Get the function for handling each request
     * 
     * @return callable
     */
    public function getHandlerFunction();
    
    /**
     * Get the port
     * 
     * @return int
     */
    public function getPort();
    
    /**
     * Get the host. '0.0.0.0' for allowing access from everywhere
     * 
     * @return string
     */
    public function getHost();
}