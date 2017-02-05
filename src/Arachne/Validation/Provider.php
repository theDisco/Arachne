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
     * @return void
     */
    public function validateAgainstSchema($stringToValidate, $schemaFilename)
    {
        $path = $this->fileLocator->locateSchemaFile($schemaFilename);
        $schemaType = $this->extractType($schemaFilename);

        $schemaValidator = $this->schemaValidatorFactory->create($schemaType);
        $schemaValidator->validateAgainstSchema($stringToValidate, $path);
    }

    /**
     * @param string $stringToValidate
     * @param string $fileName
     * @return void
     */
    public function validateStringEqualsFile($stringToValidate, $fileName)
    {
        $path = $this->fileLocator->locateResponseFile($fileName);
        $fileType = $this->extractType($fileName);

        $fileValidator = $this->fileValidatorFactory->create($fileType);
        $fileValidator->validateStringEqualsFile($stringToValidate, $path);
    }

    /**
     * @param string $filename
     * @return string
     */
    private function extractType($filename)
    {
        $filenameParts = explode('.', $filename);
        return $filenameParts[count($filenameParts)-1];
    }
}
