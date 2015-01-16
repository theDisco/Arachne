<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Mocks\Context;

use Arachne\Context\ArachneContext;
use Arachne\Http;
use Arachne\Validation;

/**
 * Class TestContext
 * @package Arachne\Mocks\Context
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class TestContext extends ArachneContext
{
    /**
     * @return Http\Client\ClientInterface|null
     */
    public function getHttpClient()
    {
        return parent::getHttpClient();
    }

    /**
     * @return Validation\Provider|null
     */
    public function getValidationProvider()
    {
        return parent::getValidationProvider();
    }
}
