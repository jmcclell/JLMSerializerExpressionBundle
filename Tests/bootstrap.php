<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    $loader = require_once __DIR__.'/../vendor/autoload.php';
    
    AnnotationRegistry::registerLoader('class_exists');

    // add our test bundle
    $loader->add('TestBundle', __DIR__ . '/Fixtures/App/src/');

    return $loader;
}

throw new \RuntimeException('Could not find vendor/autoload.php, are you sure you ran Composer?');