<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\FileSystem;

use RuntimeException;

/**
 * Class FileLocator
 * @package Arachne\FileSystem
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class FileLocator
{
    /**
     * @var array
     */
    private $dirConfig;

    /**
     * @param array $dirConfig
     */
    public function __construct(array $dirConfig)
    {
        $this->dirConfig = $dirConfig;
    }

    /**
     * @param string $schemaName
     * @param string $extension
     * @return string
     */
    public function locateSchemaFile($schemaName, $extension)
    {
        $errorMessage = "Schema file $schemaName.$extension cannot be found";

        return $this->getFilePathFor('schema_file_dir', $schemaName, $extension, $errorMessage);
    }

    /**
     * @param string $fileName
     * @param string $extension
     * @return string
     */
    public function locateRequestFile($fileName, $extension)
    {
        $errorMessage = "Request file $fileName.$extension cannot be found";

        return $this->getFilePathFor('request_file_dir', $fileName, $extension, $errorMessage);
    }

    /**
     * @param string $fileName
     * @param string $extension
     * @return string
     */
    public function locateResponseFile($fileName, $extension)
    {
        $errorMessage = "Response file $fileName.$extension cannot be found";

        return $this->getFilePathFor('response_file_dir', $fileName, $extension, $errorMessage);
    }

    /**
     * @param string $configName
     * @param string $fileName
     * @param string $extension
     * @param string $errorMessage
     * @return string
     */
    private function getFilePathFor($configName, $fileName, $extension, $errorMessage)
    {
        $responseFileDirectory = $this->getConfigValue($configName);
        $path = implode(DIRECTORY_SEPARATOR, [$responseFileDirectory, $fileName]) . '.' . $extension;
        $this->validateFileExists($path, $errorMessage);

        return $path;
    }

    /**
     * @param string $value
     * @return string
     * @throws RuntimeException
     */
    private function getConfigValue($value)
    {
        if (!isset($this->dirConfig[$value])) {
            throw new RuntimeException("`$value` missing in the configuration");
        }

        return $this->dirConfig[$value];
    }

    /**
     * @param string $path
     * @param null|string $errorMessage
     * @return void
     * @throws RuntimeException
     */
    private function validateFileExists($path, $errorMessage = null)
    {
        $errorMessage = $errorMessage ?: "File does not exist in the $path";

        if (!file_exists($path)) {
            throw new RuntimeException($errorMessage);
        }
    }
}
