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

use Arachne\Auth;
use Arachne\Exception;
use Arachne\Http;
use Arachne\Validation;
use Behat\Behat\Context\Context;
use RuntimeException;

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
     * @var Auth\BaseProvider
     */
    private $authProvider;

    /**
     * @var array
     */
    private $defaultHeaders = [];

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        if (isset($params['headers']) && is_array($params['headers'])) {
            $this->defaultHeaders = $params['headers'];
        }
    }

    /**
     * @param Http\Client\ClientInterface $client
     * @return void
     */
    public function setHttpClient(Http\Client\ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return Http\Client\ClientInterface
     */
    protected function getHttpClient()
    {
        if (!$this->client) {
            throw new RuntimeException('Http client was not set');
        }

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
     * @param Auth\BaseProvider $authProvider
     * @return void
     */
    public function setAuthProvider(Auth\BaseProvider $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    /**
     * @param array $headers
     * @return void
     */
    public function addDefaultHeaders(array $headers)
    {
        $this->defaultHeaders = array_merge($this->defaultHeaders, $headers);
    }

    /**
     * @Given I use :arg1 request method
     */
    public function iUseRequestMethod($arg1)
    {
        $this->getHttpClient()->setRequestMethod($arg1);
    }

    /**
     * @When I access the resource url :arg1
     */
    public function iAccessTheResourceUrl($arg1)
    {
        $this->getHttpClient()->setPath($arg1);
    }

    /**
     * @When I use the :arg1 file as request body
     */
    public function iUseTheFileAsRequestBody($arg1)
    {
        // TODO web service type should not come from schema validator
        $this->getHttpClient()->setRequestBody($arg1, true, 'json');
    }

    /**
     * @When I set the header :arg1 to :arg2
     */
    public function iSetTheHeaderTo($arg1, $arg2)
    {
        $this->addDefaultHeaders([$arg1 => $arg2]);
    }

    /**
     * @When I send the request
     */
    public function iSendTheRequest()
    {
        $client = $this->getHttpClient();

        if ($this->authProvider) {
            $this->authProvider->prepare($client);
        }

        if ($this->defaultHeaders) {
            foreach ($this->defaultHeaders as $header => $value) {
                $client->addHeader($header, $value);
            }
        }

        $this->response = $client->send();
    }

    /**
     * @return Http\Response\ResponseInterface
     */
    protected function getResponse()
    {
        if (!$this->response) {
            throw new RuntimeException('Request was not sent yet. Use `When I send the request` to send the request.');
        }

        return $this->response;
    }

    /**
     * @Then the status code should be :arg1
     */
    public function theStatusCodeShouldBe($arg1)
    {
        $statusCode = $this->getResponse()->getStatusCode();

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
        json_decode($this->getResponse()->getBody());

        if (json_last_error() > 0) {
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    $error = 'The maximum stack depth has been exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $error = 'Invalid or malformed JSON';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $error = 'Control character error, possibly incorrectly encoded';
                    break;
                case JSON_ERROR_SYNTAX:
                    $error = 'Syntax error';
                    break;
                default:
                    $error = 'Unknown error';
                    break;
            }

            throw new Exception\InvalidJson(sprintf('Response is not a valid JSON: %s', $error));
        }
    }

    /**
     * @Then response header :arg1 should contain :arg2
     */
    public function responseHeaderShouldContain($arg1, $arg2)
    {
        if ($this->getResponse()->getHeader($arg1) !== $arg2) {
            throw new Exception\InvalidResponseHeader;
        }
    }

    /**
     * @return Validation\Provider
     */
    protected function getValidationProvider()
    {
        if (!$this->validationProvider) {
            throw new RuntimeException('Validation provider was not set');
        }

        return $this->validationProvider;
    }

    /**
     * @Then response should validate against :arg1 schema
     */
    public function responseShouldValidateAgainstSchema($arg1)
    {
        $this->getValidationProvider()->validateAgainstSchema($this->response->getBody(), $arg1);
    }

    /**
     * @When response should be identical to :arg1 file
     */
    public function responseShouldBeIdenticalToFile($arg1)
    {
        $this->getValidationProvider()->validateStringEqualsFile($this->response->getBody(), $arg1);
    }
}
