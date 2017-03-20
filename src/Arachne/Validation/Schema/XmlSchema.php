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
use XmlValidator\XmlValidator;

/**
 * Class XmlSchema
 * @package Arachne\Validation\Schema
 * @author Jan Schädlich <schaedlich.jan@gmail.com>
 */
class XmlSchema implements ValidatorInterface
{
    const SCHEMA_TYPE = 'xsd';

    /**
     * {@inheritDoc}
     */
    public function validateAgainstSchema($stringToValidate, $schemaFile)
    {
        try {
            $validator = new XmlValidator();
            $validator->validate($stringToValidate, $schemaFile);

            if (!$validator->isValid()) {
                $errors = [];
                foreach ($validator->getErrors() as $error) {
                    $errors[] = $error->message;
                }

                throw new Exception\InvalidXml(implode(', ', $errors));
            }
        } catch (\InvalidArgumentException $exception) {
            throw new Exception\InvalidXml($exception->getMessage());
        }
    }
}
