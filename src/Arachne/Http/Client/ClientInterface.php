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

    public function send();
}
