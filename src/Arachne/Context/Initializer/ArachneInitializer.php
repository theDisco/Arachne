<?php

namespace Arachne\Context\Initializer;

use Arachne\Context\ArachneContext;
use Arachne\Http\Client\ClientInterface;
use Arachne\Validation\Provider;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;

class ArachneInitializer implements ContextInitializer
{
    private $validationProvider;

    private $httpClient;

    /**
     * @param Provider $validationProvider
     * @param ClientInterface $httpClient
     */
    public function __construct(Provider $validationProvider, ClientInterface $httpClient)
    {
        $this->validationProvider = $validationProvider;
        $this->httpClient = $httpClient;
    }

    /**
     * {@inheritDoc}
     */
    public function initializeContext(Context $context)
    {
        if (!$context instanceof ArachneContext) {
            return;
        }

        $context->setValidationProvider($this->validationProvider);
        $context->setHttpClient($this->httpClient);
    }
}
