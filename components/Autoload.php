<?php

function autoload($class_name)
{
    // весь список папок классов (в массиве)
    $array_paths = array(
        '/models/',
        '/components/'
    );

    foreach ($array_paths as $path) {
        $path = ROOT. $path . $class_name .'.php';
        if (is_file($path)) {
            include_once $path;
        }
    }

}

spl_autoload_register('autoload');
