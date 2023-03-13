<?php

namespace Sofa\Homework\src;
function custom_autoload($class_name): void
{
    $path_to_file = 'src/Entities/' . $class_name . '.php';
    if (file_exists($path_to_file))
    {
        require $path_to_file;
    }
}

spl_autoload_register('custom_autoload');
