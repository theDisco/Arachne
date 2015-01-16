<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Mocks\Http;

use Arachne\Http\Client\ClientInterface;

/**
 * Class Client
 * @package Arachne\Mocks\Http
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class Client implements ClientInterface
{
    /**
     * @var string
     */
    private $requestMethod;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $requestBody;

    /**
     * @var bool
     */
    private $requestWasSent;

    /**
     * {@inheritDoc}
     */
    public function setRequestMethod($method)
    {
        $this->requestMethod = $method;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * {@inheritDoc}
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritDoc}
     */
    public function setRequestBody($requestBody, $isFromFile, $extension = null)
    {
        $this->requestBody = array(
            'requestBody' => $requestBody,
            'isFromFile' => $isFromFile,
            'extension' => $extension,
        );
    }

    /**
     * @return array
     */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
     * {@inheritDoc}
     */
    public function send()
    {
        $this->requestWasSent = true;

        return new Response;
    }

    /**
     * @return bool
     */
    public function requestWasSent()
    {
        return $this->requestWasSent === true;
    }
}
