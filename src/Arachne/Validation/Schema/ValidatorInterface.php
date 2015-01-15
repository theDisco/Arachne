<?php

namespace Arachne\Validation\Schema;

/**
 * Interface ValidatorInterface
 * @package Arachne\Validation\Schema
 */
interface ValidatorInterface
{
    /**
     * @param string $stringToValidate
     * @param string $schemaFile
     * @return void
     */
    public function validateAgainstSchema($stringToValidate, $schemaFile);

    /**
     * @return string
     */
    public function getType();
}
