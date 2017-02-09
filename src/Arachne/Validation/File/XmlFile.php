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

use Arachne\Exception;
use Arachne\Validation\Assert;

/**
 * Class XmlFile
 * @package Arachne\Validation\File
 * @author Jan Schädlich <schaedlich.jan@gmail.com>
 */
class XmlFile implements ValidatorInterface
{
    const FILE_TYPE = 'xml';

    /**
     * {@inheritDoc}
     */
    public function validateStringEqualsFile($stringToValidate, $filePath)
    {
        Assert::assertXmlStringEqualsXmlFile($filePath, $stringToValidate);
    }
}
