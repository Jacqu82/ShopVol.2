<?php

//function __autoload($className)
//{
//    $filename = "../src/" . $className . ".php";
//    if (is_readable($filename)) {
//        require $filename;
//    }
//}

spl_autoload_register(function($className) {
    $path = __DIR__.'/src/'.str_replace('\\', '/', $className).'.php';
    if (file_exists($path)) {
        require $path;
    }
    // we don't support this class!
});
