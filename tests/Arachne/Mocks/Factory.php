<?php

/*
 * This file is part of the Arachne package.
 *
 * (c) Wojtek Gancarczyk <gancarczyk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arachne\Mocks;

use Arachne\FileSystem\FileLocator;
use Arachne\Validation\Provider;

/**
 * Class Factory
 * @package Arachne\Mocks
 * @author Wojtek Gancarczyk <gancarczyk@gmail.com>
 */
class Factory
{
    /**
     * @return FileLocator
     */
    public static function createFileLocator()
    {
        $config = array(
            'schema_file_dir' => FIXTURES_DIR . '/schemas',
            'request_file_dir' => FIXTURES_DIR . '/requests',
            'response_file_dir' => FIXTURES_DIR . '/responses',
        );

        return new FileLocator($config);
    }

    /**
     * @return Http\Client
     */
    public static function createHttpClient()
    {
        return new Http\Client;
    }

    /**
     * @return Validation\SchemaValidator
     */
    public static function createSchemaValidator()
    {
        return new Validation\SchemaValidator;
    }

    /**
     * @param string $type
     * @return Provider
     */
    public static function createValidationProvider($type)
    {
        return new Provider(
            self::createFileLocator(),
            self::createSchemaValidator(),
            $type
        );
    }
}
