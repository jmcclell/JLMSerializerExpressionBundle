<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    /**
     * @var ClassLoader $loader
     */
    $loader = require __DIR__.'/../vendor/autoload.php';

    AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
    $loader->add('TestBundle', __DIR__ . '/Fixtures/App/src/');

    return $loader;
}

throw new \RuntimeException('Could not find vendor/autoload.php, are you sure you ran Composer?');
