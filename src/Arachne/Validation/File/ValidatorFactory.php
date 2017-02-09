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
use JsonSchema\RefResolver;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;

/**
 * Class ValidatorFactory
 * @package Arachne\Validation\Schema
 * @author Jan Schädlich <schaedlich.jan@gmail.com>
 */
class ValidatorFactory
{
    /**
     * @param $fileType
     * @return JsonFile|XmlFile
     */
    public function create($fileType)
    {
        switch ($fileType) {
            case JsonFile::FILE_TYPE:
                return new JsonFile();
                break;
            case XmlFile::FILE_TYPE:
                return new XmlFile();
                break;
            default:
                return new JsonFile();
                break;
        }
    }
}
