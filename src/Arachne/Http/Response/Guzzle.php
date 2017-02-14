<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Http\Response;

use GuzzleHttp\Psr7\Response;

/**
 * Class Guzzle
 * @package Arachne\Http\Response
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class Guzzle implements ResponseInterface
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * {@inheritDoc}
     */
    public function getBody()
    {
        return $this->response->getBody()->__toString();
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader($name)
    {
        return $this->response->getHeaderLine($name);
    }
}
