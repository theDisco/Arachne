<?php

namespace Arachne\Validation\Schema;

use Arachne\Exception;
use JsonSchema\RefResolver;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;

/**
 * Class JsonSchema
 * @package Arachne\Validation\Schema
 */
class JsonSchema implements ValidatorInterface
{
    /**
     * {@inheritDoc}
     */
    public function validateAgainstSchema($stringToValidate, $schemaFile)
    {
        $retriever = new UriRetriever;
        $schema = $retriever->retrieve(sprintf('file://%s', $schemaFile));
        $data = json_decode($stringToValidate);

        $refResolver = new RefResolver($retriever);
        $refResolver->resolve($schema, 'file://' . dirname($schemaFile));

        $validator = new Validator();
        $validator->check($data, $schema);

        if (!$validator->isValid()) {
            $errors = [];

            foreach ($validator->getErrors() as $error) {
                $errors[] = $error['message'];
            }

            throw new Exception\InvalidJson(implode(', ', $errors));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return 'json';
    }
}
