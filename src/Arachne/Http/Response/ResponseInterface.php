<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Http\Response;

/**
 * Interface ResponseInterface
 * @package Arachne\Http\Response
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
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