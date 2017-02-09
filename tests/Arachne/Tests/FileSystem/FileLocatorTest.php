<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Tests\FileSystem;

use Arachne\FileSystem\FileLocator;
use Arachne\Mocks\Factory;

/**
 * Class FileLocatorTest
 * @package Arachne\Tests\FileSystem
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class FileLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileLocator
     */
    private $fileLocator;

    public function setUp()
    {
        $this->fileLocator = Factory::createFileLocator();
    }

    public function testFailOnNotExistingConfigurationValue()
    {
        $this->setExpectedException('RuntimeException', '`request_file_dir` missing in the configuration');
        $fileLocator = new FileLocator(array());
        $fileLocator->locateRequestFile('not', 'existing');
    }

    public function testLocateSchemaFile()
    {
        $actualPath = $this->fileLocator->locateSchemaFile('test', 'json');
        $expectedPath = FIXTURES_DIR . '/schemas/test.json';
        $this->assertSame($expectedPath, $actualPath);

        $actualPath = $this->fileLocator->locateSchemaFile('test', 'xml');
        $expectedPath = FIXTURES_DIR . '/schemas/test.xml';
        $this->assertSame($expectedPath, $actualPath);
    }

    public function testFailLocatingNotExistingSchemaFile()
    {
        $this->setExpectedException('RuntimeException', 'Schema file not.existing cannot be found');
        $this->fileLocator->locateSchemaFile('not', 'existing');
    }

    public function testLocateRequestFile()
    {
        $actualPath = $this->fileLocator->locateRequestFile('test', 'json');
        $expectedPath = FIXTURES_DIR . '/requests/test.json';
        $this->assertSame($expectedPath, $actualPath);

        $actualPath = $this->fileLocator->locateRequestFile('test', 'xml');
        $expectedPath = FIXTURES_DIR . '/requests/test.xml';
        $this->assertSame($expectedPath, $actualPath);
    }

    public function testFailLocatingNotExistingRequestFile()
    {
        $this->setExpectedException('RuntimeException', 'Request file not.existing cannot be found');
        $this->fileLocator->locateRequestFile('not', 'existing');
    }

    public function testLocateResponseFile()
    {
        $actualPath = $this->fileLocator->locateResponseFile('test', 'json');
        $expectedPath = FIXTURES_DIR . '/responses/test.json';
        $this->assertSame($expectedPath, $actualPath);

        $actualPath = $this->fileLocator->locateResponseFile('test', 'xml');
        $expectedPath = FIXTURES_DIR . '/responses/test.xml';
        $this->assertSame($expectedPath, $actualPath);
    }

    public function testFailLocatingNotExistingResponseFile()
    {
        $this->setExpectedException('RuntimeException', 'Response file not.existing cannot be found');
        $this->fileLocator->locateResponseFile('not', 'existing');
    }
}
