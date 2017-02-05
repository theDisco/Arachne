<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Validation\Schema;

use Arachne\Exception;
use JsonSchema\RefResolver;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;

/**
 * Class ValidatorFactory
 * @package Arachne\Validation\Schema
 * @author Jan Schädlich <schaedlich.jan@gmail.com>
 */
class ValidatorFactory
{
    /**
     * @param $schemaType
     * @return JsonSchema|XmlSchema
     */
    public function create($schemaType)
    {
        switch ($schemaType) {
            case JsonSchema::SCHEMA_TYPE:
                return new JsonSchema();
                break;
            case XmlSchema::SCHEMA_TYPE:
                return new XmlSchema();
                break;
        }

        throw new \RuntimeException(sprintf('No Schema\Validator found for schemaType (%s)', $schemaType));
    }
}
