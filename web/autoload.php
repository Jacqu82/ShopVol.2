<?php

function __autoload($className)
{
    $filename = "../src/" . $className . ".php";
    if (is_readable($filename)) {
        require $filename;
    }
}
