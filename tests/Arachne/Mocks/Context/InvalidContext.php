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

use Arachne\Http;
use Arachne\Validation;
use Behat\Behat\Context\Context;

/**
 * Class InvalidContext
 * @package Arachne\Mocks\Context
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class InvalidContext implements Context
{
    /**
     * @var Http\Client\ClientInterface
     */
    private $client;

    /**
     * @var Validation\Provider
     */
    private $validationProvider;

    /**
     * @param Http\Client\ClientInterface $client
     * @return void
     */
    public function setHttpClient(Http\Client\ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return Http\Client\ClientInterface|null
     */
    public function getHttpClient()
    {
        return $this->client;
    }

    /**
     * @param Validation\Provider $validationProvider
     * @return void
     */
    public function setValidationProvider(Validation\Provider $validationProvider)
    {
        $this->validationProvider = $validationProvider;
    }

    /**
     * @return Validation\Provider|null
     */
    public function getValidationProvider()
    {
        return $this->validationProvider;
    }
}
