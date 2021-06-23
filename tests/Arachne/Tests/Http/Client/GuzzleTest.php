<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Tests\Http\Client;

use Arachne\Http\Client\Guzzle;
use Arachne\Mocks\Factory;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7;
use PHPUnit\Framework\TestCase;

/**
 * Class GuzzleTest
 * @package Arachne\Tests\Http\Client
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class GuzzleTest extends TestCase
{
    public function testDoNotFailIfResponseCodeIs404()
    {
        $client = new Guzzle('http://www.example.com', Factory::createFileLocator());
        $client->setRequestMethod('GET');
        $client->setPath('/non/existent');
        $client->applyHandler(function() {
            return HandlerStack::create(new MockHandler([
                new Psr7\Response(404, [], 'not_existent')
            ]));
        });
        $response = $client->send();
        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('not_existent', $response->getBody());
    }

    public function testPrintWarningIfHeaderGetOverwritten()
    {
        $this->expectOutputString('Overwriting header `X-Test` with `test` (previous value `testing`)');

        $client = new Guzzle('http://www.example.com', Factory::createFileLocator());
        $client->addHeader('X-Test', 'testing');
        $client->addHeader('X-Test', 'test');
    }
}
