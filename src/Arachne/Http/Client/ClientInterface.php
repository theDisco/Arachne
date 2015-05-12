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

/**
 * Interface ClientInterface
 * @package Arachne\Http\Client
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
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

    /**
     * @param string $header
     * @param string $value
     * @return void
     */
    public function addHeader($header, $value);

    /**
     * @param string $requestBody
     * @param bool $isFromFile
     * @param null|string $extension
     * @return void
     */
    public function setRequestBody($requestBody, $isFromFile = false, $extension = null);

    /**
     * @return Response\ResponseInterface
     */
    public function send();
}
