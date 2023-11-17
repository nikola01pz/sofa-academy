<?php

declare(strict_types=1);

require dirname(__DIR__).'/simplefw/Autoload/Autoloader.php';

\SimpleFW\Autoload\Autoloader::createAndRegister([
    'SimpleFW\\' => dirname(__DIR__).'/simplefw',
    'App\\' => dirname(__DIR__).'/src',
]);
