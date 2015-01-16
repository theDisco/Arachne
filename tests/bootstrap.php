<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('FIXTURES_DIR', __DIR__ . '/Resources/fixtures');

$classLoader = new \Symfony\Component\ClassLoader\ClassLoader;
$classLoader->addPrefix('Arachne', __DIR__);
$classLoader->register();