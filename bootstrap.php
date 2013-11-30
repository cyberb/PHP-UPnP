<?php

function loader($class)
{
    $srcMain = __DIR__ .'/src/main/';
    $file = $srcMain.strtolower($class). '.class.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

spl_autoload_register('loader');