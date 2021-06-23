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
use PHPUnit\Framework\TestCase;

/**
 * Class XmlSchemaTest
 * @package Arachne\Tests\Validation\Schema
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class XmlSchemaTest extends TestCase
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
        $this->expectException(\Arachne\Exception\InvalidXml::class);
        $this->expectExceptionMessage("Element 'invalid': This element is not expected. Expected is ( child_string ).");
        $validator = new XmlSchema;
        $validator->validateAgainstSchema(
            file_get_contents(implode(DIRECTORY_SEPARATOR, [FIXTURES_DIR, 'responses', 'test-invalid.xml'])),
            implode(DIRECTORY_SEPARATOR, [FIXTURES_DIR, 'schemas', 'test.xml'])
        );
    }
}
