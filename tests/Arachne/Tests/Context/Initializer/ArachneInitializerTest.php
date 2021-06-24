<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Tests\Context\Initializer;

use Arachne\Auth\DummyProvider;
use Arachne\Context\Initializer\ArachneInitializer;
use Arachne\Mocks;
use PHPUnit\Framework\TestCase;

/**
 * Class ArachneInitializerTest
 * @package Arachne\Tests\Context\Initilizer
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class ArachneInitializerTest extends TestCase
{
    /**
     * @var ArachneInitializer
     */
    private $initializer;

    public function setUp(): void
    {
        $this->initializer = new ArachneInitializer(
            Mocks\Factory::createValidationProvider(),
            Mocks\Factory::createHttpClient()
        );
    }

    public function testDoNotInitializeInvalidContext()
    {
        $context = new Mocks\Context\InvalidContext;
        $this->initializer->initializeContext($context);
        $this->assertNull($context->getHttpClient());
        $this->assertNull($context->getValidationProvider());
    }

    public function testInitializeArachneContext()
    {
        $context = new Mocks\Context\TestContext;
        $this->initializer->initializeContext($context);
        $this->assertInstanceOf('\Arachne\Http\Client\ClientInterface', $context->getHttpClient());
        $this->assertInstanceOf('\Arachne\Validation\Provider', $context->getValidationProvider());
    }

    public function testAuthenticateIfProviderPresent()
    {
        $httpClient = Mocks\Factory::createHttpClient();
        $authProvider = new DummyProvider($httpClient);
        $initializer = new ArachneInitializer(
            Mocks\Factory::createValidationProvider(),
            $httpClient,
            $authProvider
        );
        $context = new Mocks\Context\TestContext;
        $initializer->initializeContext($context);

        $this->assertTrue($authProvider->wasAuthenticated());
    }

    public function testSetHeadersIfHeadersPresentInConfig()
    {
        $httpClient = Mocks\Factory::createHttpClient();
        $initializer = new ArachneInitializer(
            Mocks\Factory::createValidationProvider(),
            $httpClient,
            null,
            ['X-Test-Header' => 'Test Value']
        );
        $context = new Mocks\Context\TestContext;
        $initializer->initializeContext($context);
        $context->iSendTheRequest();

        $this->assertEquals(['X-Test-Header' => 'Test Value'], $httpClient->getHeaders());
    }
}
