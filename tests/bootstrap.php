<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

call_user_func(function () {
    if (!is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
        throw new \RuntimeException('Did not find vendor/autoload.php. Did you run "composer install --dev"?');
    }

    $loader = require $autoloadFile;
    $loader->addPsr4('RSSchermer\Tests\\', __DIR__);

    AnnotationRegistry::registerLoader('class_exists');
});
