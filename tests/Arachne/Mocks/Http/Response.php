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

use Arachne\Http\Response\ResponseInterface;

/**
 * Class Response
 * @package Arachne\Mocks\Http
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class Response implements ResponseInterface
{
    /**
     * @return int
     */
    public function getStatusCode()
    {
        return 200;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return json_encode(array('test' => 'value'));
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHeader($name)
    {
        return 'Test Header';
    }
}
