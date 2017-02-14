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
use GuzzleHttp\Exception;
use GuzzleHttp\Psr7;

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
    private $headers;
    private $applyHandlerCallback;

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
        assert(in_array($method, array('GET', 'POST', 'PUT', 'DELETE', 'PATCH')));
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
    public function addHeader($header, $value)
    {
        if (isset($this->headers[$header])) {
            echo "Overwriting header `$header` with `$value` (previous value `{$this->headers[$header]}`)";
        }

        $this->headers[$header] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function setRequestBody($requestBody, $isFromFile = false, $extension = null)
    {
        if ($isFromFile) {
            $path = $this->fileLocator->locateRequestFile($requestBody, $extension);
            $requestBody = fopen($path, 'r');
        }

        $this->requestBody = Psr7\stream_for($requestBody);
    }

    /**
     * {@inheritDoc}
     */
    public function send()
    {
        $handler = [];
        if ($this->applyHandlerCallback) {
            $handler['handler'] = call_user_func($this->applyHandlerCallback);
        }

        $client = new Client($handler);

        $request = new Psr7\Request(
            $this->requestMethod,
            $this->baseUrl . $this->path,
            $this->headers ? $this->headers : [],
            $this->requestBody
        );

        $this->reset();

        try {
            $response = $client->send($request);
        } catch (Exception\ClientException $exception) {
            $response = $exception->getResponse();
        }

        return new Response\Guzzle($response);
    }

    /**
     * @param callable $callback
     * @return void
     */
    public function applyHandler(callable $callback)
    {
        $this->applyHandlerCallback = $callback;
    }

    /**
     * @return void
     */
    private function reset()
    {
        $this->path = null;
        $this->requestMethod = null;
        $this->requestBody = null;
        $this->headers = [];
    }
}
