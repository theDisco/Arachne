<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Validation;

use Arachne\FileSystem\FileLocator;

/**
 * Class Provider
 * @package Arachne\Validation
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class Provider
{
    /**
     * @var Schema\ValidatorInterface
     */
    private $schemaValidator;

    /**
     * @var Assert
     */
    private $assertion;

    /**
     * @var FileLocator
     */
    private $fileLocator;

    /**
     * @var string
     */
    private $type;

    /**
     * @param FileLocator $fileLocator
     * @param Schema\ValidatorInterface $schemaValidator
     * @param string $type
     */
    public function __construct(FileLocator $fileLocator, Schema\ValidatorInterface $schemaValidator, $type)
    {
        $this->fileLocator = $fileLocator;
        $this->schemaValidator = $schemaValidator;
        $this->type = $type;
        $this->assertion = new Assert;
    }

    /**
     * @param string $stringToValidate
     * @param string $schemaName
     * @return void
     */
    public function validateAgainstSchema($stringToValidate, $schemaName)
    {
        $path = $this->fileLocator->locateSchemaFile($schemaName, $this->type);
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
        $filePath = $this->fileLocator->locateResponseFile($fileName, $this->type);
        $this->assertion->assertJsonStringEqualsJsonFile($filePath, $stringToValidate);
    }
}
