<?php

function autoload_lib_class($class) {
    $paths = explode('\\', $class);
    $classObj = array_pop($paths);
    $filepath = '';
    foreach ($paths as $path) {
        $filepath .= strtolower($path).'/';
    }
    $filepath .= $classObj.'.php';
    $basepath =  __DIR__.'/../../';
    if (file_exists($basepath.$filepath)) {
        require $basepath.$filepath;
    }
}

spl_autoload_register('autoload_lib_class');