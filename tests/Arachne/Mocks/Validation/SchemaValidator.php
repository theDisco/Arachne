<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Mocks\Validation;

use Arachne\Validation\Schema\ValidatorInterface;

/**
 * Class SchemaValidator
 * @package Arachne\Mocks\Validation
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class SchemaValidator implements ValidatorInterface
{
    /**
     * @param string $stringToValidate
     * @param string $schemaFile
     * @return void
     */
    public function validateAgainstSchema($stringToValidate, $schemaFile)
    {
        // TODO: Implement validateAgainstSchema() method.
    }
}