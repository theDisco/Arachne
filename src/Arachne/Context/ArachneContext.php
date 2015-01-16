<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Context;

use Arachne\Exception;
use Arachne\Http;
use Arachne\Validation;
use Behat\Behat\Context\Context;

/**
 * Class ArachneContext
 * @package Arachne\Context
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class ArachneContext implements Context
{
    /**
     * @var Http\Client\ClientInterface
     */
    private $client;

    /**
     * @var Http\Response\ResponseInterface
     */
    private $response;

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
     * @param Validation\Provider $validationProvider
     * @return void
     */
    public function setValidationProvider(Validation\Provider $validationProvider)
    {
        $this->validationProvider = $validationProvider;
    }

    /**
     * @Given I use :arg1 request method
     */
    public function iUseRequestMethod($arg1)
    {
        $this->client->setRequestMethod($arg1);
    }

    /**
     * @When I access the resource url :arg1
     */
    public function iAccessTheResourceUrl($arg1)
    {
        $this->client->setPath($arg1);
    }

    /**
     * @When I use the :arg file as request body
     */
    public function iUseTheFileAsRequestBody($arg1)
    {
        // TODO web service type should not come from schema validator
        $this->client->setRequestBody($arg1, true, 'json');
    }

    /**
     * @When I send the request
     */
    public function iSendTheRequest()
    {
        $this->response = $this->client->send();
    }

    /**
     * @Then the status code should be :arg1
     */
    public function theStatusCodeShouldBe($arg1)
    {
        $statusCode = $this->response->getStatusCode();

        if ($statusCode !== intval($arg1)) {
            throw new Exception\InvalidStatusCode(
                "Resource returned status code $statusCode, status code $arg1 expected."
            );
        }
    }

    /**
     * @Then response should be a valid JSON
     */
    public function responseShouldBeAValidJson()
    {
        json_decode($this->response->getBody());

        if (json_last_error() > 0) {
            throw new Exception\InvalidJson(
                sprintf('Response is not a valid JSON: %s', json_last_error_msg())
            );
        }
    }

    /**
     * @Then response header :arg1 should contain :arg2
     */
    public function responseHeaderShouldContain($arg1, $arg2)
    {
        if ($this->response->getHeader($arg1) !== $arg2) {
            throw new Exception\InvalidResponseHeader;
        }
    }

    /**
     * @Then response should validate against :arg1 schema
     */
    public function responseShouldValidateAgainstSchema($arg1)
    {
        $this->validationProvider->validateAgainstSchema(
            $this->response->getBody(),
            $arg1
        );
    }

    /**
     * @When response should be identical to :arg1 file
     */
    public function responseShouldBeIdenticalToFile($arg1)
    {
        $this->validationProvider->validateStringEqualsFile($this->response->getBody(), $arg1);
    }
}
