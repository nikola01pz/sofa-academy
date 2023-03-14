<?php

namespace Sofa\Homework;

spl_autoload_register(function (string $className)
{
    $className = str_replace('\\', '/', $className);
    $className = str_replace('Sofa/Homework/', '', $className);
    $filePath = __DIR__.'/Entities/'.$className.'.php';

    if (file_exists($filePath))
    {
        require $filePath;
    }
});
