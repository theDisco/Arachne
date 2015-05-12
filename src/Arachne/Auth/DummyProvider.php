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
 * Class DummyProvider
 * @package Arachne\Auth
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class DummyProvider extends BaseProvider
{
    private $authenticated = false;
    private $prepared = false;

    /**
     * {@inheritDoc}
     */
    public function authenticate()
    {
        $this->authenticated = true;

        return true;
    }

    /**
     * @param ClientInterface $client
     * @return void
     */
    public function prepare(ClientInterface $client)
    {
        $this->prepared = true;
    }

    /**
     * @return bool
     */
    public function wasAuthenticated()
    {
        return $this->authenticated;
    }

    /**
     * @return bool
     */
    public function wasPrepared()
    {
        return $this->prepared;
    }
}
