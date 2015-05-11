<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Auth;

use Arachne\Http\Client\ClientInterface;

/**
 * Class BaseProvider
 * @package Arachne\Auth
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
abstract class BaseProvider
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return ClientInterface
     */
    protected function getClient()
    {
        return $this->client;
    }

    /**
     * Return true, if authentication was successful, false otherwise.
     * Any non true value returned by this method will treated as false
     * and will stop execution of the test.
     *
     * @return bool
     */
    abstract public function authenticate();

    /**
     * @return mixed
     */
    abstract public function getResult();
}
