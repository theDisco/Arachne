<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Validation\File;

/**
 * Interface ValidatorInterface
 * @package Arachne\Validation\File
 * @author Jan Schädlich <schaedlich.jan@gmail.com>
 */
interface ValidatorInterface
{
    /**
     * @param string $stringToValidate
     * @param string $filePath
     * @return void
     */
    public function validateStringEqualsFile($stringToValidate, $filePath);
}
