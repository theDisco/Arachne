<?php

namespace Arachne\Tests\FileSystem;

use Arachne\FileSystem\FileLocator;

class FileLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileLocator
     */
    private $fileLocator;

    public function setUp()
    {
        $config = array(
            'schema_file_dir' => FIXTURES_DIR . '/schemas',
            'request_file_dir' => FIXTURES_DIR . '/requests',
            'response_file_dir' => FIXTURES_DIR . '/responses',
        );
        $this->fileLocator = new FileLocator($config);
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
    }

    public function testFailLocatingNotExistingResponseFile()
    {
        $this->setExpectedException('RuntimeException', 'Response file not.existing cannot be found');
        $this->fileLocator->locateResponseFile('not', 'existing');
    }
}
