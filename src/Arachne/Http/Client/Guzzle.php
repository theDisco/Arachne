<?php

namespace Arachne\Http\Client;

use Arachne\Http\Response;
use GuzzleHttp\Client;

class Guzzle implements ClientInterface
{
    private $baseUrl;
    private $requestMethod;
    private $path;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function setRequestMethod($method)
    {
        assert(in_array($method, array('GET', 'POST')));
        $this->requestMethod = $method;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function send()
    {
        $client = new Client;
        $request = $client->createRequest($this->requestMethod, $this->baseUrl . $this->path);

        return new Response\Guzzle($client->send($request));
    }
}