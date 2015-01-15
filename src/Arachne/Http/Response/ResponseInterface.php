<?php

namespace Arachne\Http\Response;


interface ResponseInterface
{
    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @return string
     */
    public function getBody();

    /**
     * @param string $name
     * @return string
     */
    public function getHeader($name);
}