<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Tests\Validation\Schema;

use Arachne\Validation\Schema\XmlSchema;

/**
 * Class XmlSchemaTest
 * @package Arachne\Tests\Validation\Schema
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class XmlSchemaTest extends \PHPUnit_Framework_TestCase
{
    public function testDoesNotFailOnValidSchema()
    {
        $validator = new XmlSchema;
        $validator->validateAgainstSchema(
            file_get_contents(implode(DIRECTORY_SEPARATOR, [FIXTURES_DIR, 'responses', 'test.xml'])),
            implode(DIRECTORY_SEPARATOR, [FIXTURES_DIR, 'schemas', 'test.xml'])
        );
    }

    public function testFailsOnInvalidSchema()
    {
        $this->setExpectedException(
          \Arachne\Exception\InvalidXml::class,
          "Element 'invalid': This element is not expected. Expected is ( child_string )."
        );
        $validator = new XmlSchema;
        $validator->validateAgainstSchema(
            file_get_contents(implode(DIRECTORY_SEPARATOR, [FIXTURES_DIR, 'responses', 'test-invalid.xml'])),
            implode(DIRECTORY_SEPARATOR, [FIXTURES_DIR, 'schemas', 'test.xml'])
        );
    }
}
