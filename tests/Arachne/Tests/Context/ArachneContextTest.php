<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Tests\Context;

use Arachne\Auth\DummyProvider;
use Arachne\Context\ArachneContext;
use Arachne\Mocks\Factory;
use Arachne\Mocks\Http\Client;

/**
 * Class ArachneContextTest
 * @package Arachne\Tests\FileSystem
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class ArachneContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArachneContext
     */
    private $context;

    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = Factory::createHttpClient();
        $this->context = new ArachneContext;
        $this->context->setHttpClient($this->client);
    }

    public function testFailIfHttpClientWasNotSet()
    {
        $this->setExpectedException('RuntimeException', 'Http client was not set');
        $context = new ArachneContext;
        $context->iSendTheRequest();
    }

    public function testIUseRequestMethod()
    {
        $this->context->iUseRequestMethod('POST');
        $this->assertSame('POST', $this->client->getRequestMethod());
    }

    public function testIAccessTheResourceUrl()
    {
        $this->context->iAccessTheResourceUrl('/test/path');
        $this->assertSame('/test/path', $this->client->getPath());
    }

    public function testIUseTheFileAsRequestBody()
    {
        $this->context->iUseTheFileAsRequestBody('file');
        $expectedResult = array(
            'requestBody' => 'file',
            'isFromFile' => true,
            'extension' => 'json',
        );
        $this->assertSame($expectedResult, $this->client->getRequestBody());
    }

    public function testISendTheRequest()
    {
        $this->context->iSendTheRequest();
        $this->assertTrue($this->client->requestWasSent());
    }

    public function testTheStatusCodeShouldBe()
    {
        $this->context->iSendTheRequest();
        $this->assertNull($this->context->theStatusCodeShouldBe(200));
    }

    public function testFailTheStatusCodeShouldBeIfTheStatusCodeIsInvalid()
    {
        $this->setExpectedException(
            '\Arachne\Exception\InvalidStatusCode',
            'Resource returned status code 200, status code 400 expected.'
        );
        $this->context->iSendTheRequest();
        $this->context->theStatusCodeShouldBe(400);
    }

    public function testPrepareAuthProvider()
    {
        $provider = new DummyProvider($this->client);
        $provider->authenticate();
        $this->context->setAuthProvider($provider);
        $this->context->iSendTheRequest();

        $this->assertTrue($provider->wasPrepared());
    }

    public function testAddDefaultHeaders()
    {
        $client = Factory::createHttpClient();
        $context = new ArachneContext(['headers' => ['X-Test-Header' => 'Test-Value']]);
        $context->setHttpClient($client);
        $context->iSendTheRequest();

        $this->assertEquals(['X-Test-Header' => 'Test-Value'], $client->getHeaders());
    }
}
