<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Http\Client;

use Arachne\Http\Response;
use GuzzleHttp\Client;

/**
 * Class Guzzle
 * @package Arachne\Http\Client
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class Guzzle implements ClientInterface
{
    private $baseUrl;
    private $requestMethod;
    private $path;
    private $requestBody;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function setRequestMethod($method)
    {
        assert(in_array($method, array('GET', 'POST', 'PUT', 'DELETE')));
        $this->requestMethod = $method;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
    }

    public function send()
    {
        $client = new Client;
        $request = $client->createRequest($this->requestMethod, $this->baseUrl . $this->path);

        return new Response\Guzzle($client->send($request));
    }
}