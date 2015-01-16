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

/**
 * Interface ValidatorInterface
 * @package Arachne\Validation\Schema
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
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
