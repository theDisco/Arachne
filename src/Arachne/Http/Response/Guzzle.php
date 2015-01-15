<?php

namespace Arachne\Http\Response;

use GuzzleHttp\Message\Response;

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
        return $this->response->getHeader($name);
    }
}
