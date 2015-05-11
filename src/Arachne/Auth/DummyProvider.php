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

/**
 * Class DummyProvider
 * @package Arachne\Auth
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class DummyProvider extends BaseProvider
{
    private $authenticated = false;

    /**
     * {@inheritDoc}
     */
    public function authenticate()
    {
        $this->authenticated = true;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getResult()
    {
        return [
            'authenticated' => $this->authenticated,
        ];
    }
}
