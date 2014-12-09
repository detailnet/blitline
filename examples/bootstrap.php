<?php

$loader = null;
$basePath = realpath(__DIR__ . '/..') . '/';

if (file_exists($basePath . 'vendor/autoload.php')) {
    $loader = include $basePath . 'vendor/autoload.php';
} else {
    throw new RuntimeException(
        $basePath . 'vendor/autoload.php could not be found. Did you run `php composer.phar install`?'
    );
}

$loader->add('Detail\Blitline', $basePath . 'src/Detail/Blitline');

if (!file_exists('config.php')) {
    throw new RuntimeException(
        'Missing configuration file "config.php"; make a copy of "config.php.dist" and update it'
    );
}

return require 'config.php';
