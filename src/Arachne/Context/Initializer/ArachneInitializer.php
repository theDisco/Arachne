<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Context\Initializer;

use Arachne\Auth\BaseProvider;
use Arachne\Context\ArachneContext;
use Arachne\Http\Client\ClientInterface;
use Arachne\Validation\Provider;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;

/**
 * Class ArachneInitializer
 * @package Arachne\Context\Initializer
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class ArachneInitializer implements ContextInitializer
{
    /**
     * @var Provider
     */
    private $validationProvider;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var BaseProvider|null
     */
    private $authProvider;

    /**
     * @var array
     */
    private $defaultHeaders;

    /**
     * @param Provider $validationProvider
     * @param ClientInterface $httpClient
     * @param BaseProvider|null $authProvider
     * @param array $defaultHeaders
     */
    public function __construct(
        Provider $validationProvider,
        ClientInterface $httpClient,
        BaseProvider $authProvider = null,
        array $defaultHeaders = array()
    ) {
        $this->validationProvider = $validationProvider;
        $this->httpClient = $httpClient;
        $this->authProvider = $authProvider;
        $this->defaultHeaders = $defaultHeaders;
    }

    /**
     * {@inheritDoc}
     */
    public function initializeContext(Context $context)
    {
        if (!$context instanceof ArachneContext) {
            return;
        }

        if ($this->authProvider) {
            $this->authProvider->authenticate();
            $context->setAuthProvider($this->authProvider);
        }

        if ($this->defaultHeaders) {
            $context->addDefaultHeaders($this->defaultHeaders);
        }

        $context->setValidationProvider($this->validationProvider);

        $context->setHttpClient($this->httpClient);
    }
}
