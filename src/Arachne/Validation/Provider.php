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
     * @var Schema\ValidatorFactory
     */
    private $schemaValidatorFactory;

    /**
     * @var File\ValidatorFactory
     */
    private $fileValidatorFactory;

    /**
     * @var FileLocator
     */
    private $fileLocator;

    /**
     * @param FileLocator $fileLocator
     * @param Schema\ValidatorFactory $schemaValidatorFactory
     * @param File\ValidatorFactory $fileValidatorFactory
     */
    public function __construct(
        FileLocator $fileLocator,
        Schema\ValidatorFactory $schemaValidatorFactory,
        File\ValidatorFactory $fileValidatorFactory
    ) {
        $this->fileLocator = $fileLocator;
        $this->schemaValidatorFactory = $schemaValidatorFactory;
        $this->fileValidatorFactory = $fileValidatorFactory;
    }

    /**
     * @param string $stringToValidate
     * @param string $schemaFilename
     * @param string $schemaFileType
     * @return void
     */
    public function validateAgainstSchema($stringToValidate, $schemaFilename, $schemaFileType)
    {
        $path = $this->fileLocator->locateSchemaFile($schemaFilename, $schemaFileType);

        $schemaValidator = $this->schemaValidatorFactory->create($schemaFileType);
        $schemaValidator->validateAgainstSchema($stringToValidate, $path);
    }

    /**
     * @param string $stringToValidate
     * @param string $fileFileName
     * @param string $fileFileType
     * @return void
     */
    public function validateStringEqualsFile($stringToValidate, $fileFileName, $fileFileType)
    {
        $path = $this->fileLocator->locateResponseFile($fileFileName, $fileFileType);

        $fileValidator = $this->fileValidatorFactory->create($fileFileType);
        $fileValidator->validateStringEqualsFile($stringToValidate, $path);
    }
}
