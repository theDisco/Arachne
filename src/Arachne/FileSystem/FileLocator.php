<?php

namespace Arachne\FileSystem;

use RuntimeException;

/**
 * Class FileLocator
 * @package Arachne\FileSystem
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
     * @throws RuntimeException
     */
    public function locateSchemaFile($schemaName, $extension)
    {
        $schemaFileDirectory = $this->getSchemaFileDirectory();
        $path = implode(DIRECTORY_SEPARATOR, [$schemaFileDirectory, $schemaName]) . '.' . $extension;
        $this->validateFileExists(
            $path,
            "Schema file $schemaName.$extension cannot be located in $schemaFileDirectory"
        );

        return $path;
    }

    /**
     * @param string $fileName
     * @param string $extension
     * @return string
     * @throws RuntimeException
     */
    public function locateResponseFile($fileName, $extension)
    {
        $responseFileDirectory = $this->getResponseFileDirectory();
        $path = implode(DIRECTORY_SEPARATOR, [$responseFileDirectory, $fileName]) . '.' . $extension;
        $this->validateFileExists(
            $path,
            "Response file $fileName.$extension cannot be located in $responseFileDirectory"
        );

        return $path;
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    private function getSchemaFileDirectory()
    {
        return $this->getConfigValue('schema_file_dir');
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    private function getResponseFileDirectory()
    {
        return $this->getConfigValue('response_file_dir');
    }

    /**
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
