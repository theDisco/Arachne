<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Tests\ServiceContainer;

use Arachne\ServiceContainer\ArachneExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ArachneExtensionTest
 * @package Arachne\Tests\ServiceContainer
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class ArachneExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testDoNotFailIfAuthProviderNotSetUp()
    {
        $config = array(
            'base_url' => '',
            'paths' => [],
        );
        $containerBuilder = new ContainerBuilder;
        $extension = new ArachneExtension;
        $extension->load($containerBuilder, $config);
        $containerBuilder->compile();
        $this->assertSame(
            'Arachne\Context\Initializer\ArachneInitializer',
            $containerBuilder->getDefinition(ArachneExtension::CONTEXT_INITIALIZER)->getClass()
        );
    }
}
