<?php

namespace Arachne\Http\Client;

/**
 * Interface ClientInterface
 * @package Arachne\Http\Client
 */
interface ClientInterface
{
    /**
     * @param string $method
     * @return void
     */
    public function setRequestMethod($method);

    /**
     * @param string $path
     * @return void
     */
    public function setPath($path);

    public function send();
}
