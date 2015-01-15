<?php

namespace Arachne\Validation;

use Arachne\FileSystem\FileLocator;

class Provider
{
    private $schemaValidator;

    private $assertion;

    private $fileLocator;

    public function __construct(FileLocator $fileLocator, Schema\ValidatorInterface $schemaValidator)
    {
        $this->fileLocator = $fileLocator;
        $this->schemaValidator = $schemaValidator;
        $this->assertion = new Assert;
    }

    /**
     * @param string $stringToValidate
     * @param string $schemaName
     * @return void
     */
    public function validateAgainstSchema($stringToValidate, $schemaName)
    {
        $path = $this->fileLocator->locateSchemaFile($schemaName, $this->schemaValidator->getType());
        $this->schemaValidator->validateAgainstSchema($stringToValidate, $path);
    }

    /**
     * @param string $stringToValidate
     * @param string $fileName
     * @return void
     */
    public function validateStringEqualsFile($stringToValidate, $fileName)
    {
        // TODO provide type based validation
        $filePath = $this->fileLocator->locateResponseFile($fileName, $this->schemaValidator->getType());
        $this->assertion->assertJsonStringEqualsJsonFile($filePath, $stringToValidate);
    }
}