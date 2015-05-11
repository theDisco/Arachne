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

use Arachne\FileSystem\FileLocator;
use Arachne\Http\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Stream;

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
    private $fileLocator;

    /**
     * @param string $baseUrl
     * @param FileLocator $fileLocator
     */
    public function __construct($baseUrl, FileLocator $fileLocator)
    {
        $this->baseUrl = $baseUrl;
        $this->fileLocator = $fileLocator;
    }

    /**
     * {@inheritDoc}
     */
    public function setRequestMethod($method)
    {
        assert(in_array($method, array('GET', 'POST', 'PUT', 'DELETE')));
        $this->requestMethod = $method;
    }

    /**
     * {@inheritDoc}
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function setRequestBody($requestBody, $isFromFile, $extension = null)
    {
        if ($isFromFile) {
            $path = $this->fileLocator->locateRequestFile($requestBody, $extension);
            $requestBody = fopen($path, 'r');
        }

        $this->requestBody = Stream\Stream::factory($requestBody);
    }

    /**
     * {@inheritDoc}
     */
    public function send()
    {
        $client = new Client;
        $request = $client->createRequest($this->requestMethod, $this->baseUrl . $this->path);

        if ($this->requestBody) {
            $request->setBody($this->requestBody);
        }

        $this->reset();

        return new Response\Guzzle($client->send($request));
    }

    /**
     * @return void
     */
    private function reset()
    {
        $this->path = null;
        $this->requestMethod = null;
        $this->requestBody = null;
    }
}
