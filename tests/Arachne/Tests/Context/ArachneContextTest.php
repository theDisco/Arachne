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

    /**
     * @dataProvider createContextWithHeader
     */
    public function testAddDefaultHeaders(ArachneContext $context, Client $client)
    {
        $context->iSendTheRequest();

        $this->assertEquals(['X-Test-Header' => 'Test-Value'], $client->getHeaders());
    }

    /**
     * @dataProvider createContextWithHeader
     */
    public function testFeatureCanOverwriteDefaultHeader(ArachneContext $context, Client $client)
    {
        $context->iSetTheHeaderTo('X-Test-Header', 'Test-Value-Overwritten');
        $context->iSendTheRequest();

        $this->assertEquals(['X-Test-Header' => 'Test-Value-Overwritten'], $client->getHeaders());
    }

    /**
     * @dataProvider createContextWithHeader
     */
    public function testInitializerOverwritesDefaultHeaders(ArachneContext $context, Client $client)
    {
        $context->addDefaultHeaders(['X-Test-Header' => 'Test-Value-Overwritten-Config']);
        $context->iSendTheRequest();

        $this->assertEquals(['X-Test-Header' => 'Test-Value-Overwritten-Config'], $client->getHeaders());
    }

    public function createContextWithHeader()
    {
        $client = Factory::createHttpClient();
        $context = new ArachneContext(['headers' => ['X-Test-Header' => 'Test-Value']]);
        $context->setHttpClient($client);

        return [[$context, $client]];
    }

    public function testDoNotPerformAuthIfAnonymousUser()
    {
        $provider = new DummyProvider($this->client);
        $provider->authenticate();
        $this->context->setAuthProvider($provider);
        $this->context->iAmAnAnonymousUser();
        $this->context->iSendTheRequest();

        $this->assertFalse($provider->wasPrepared());
    }
}
