<?php

// Check if we find Composer and use it!
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    // Fallback to a stupid Autoloader -> PLS USE COMPOSER IF YOU CAN <3
    spl_autoload_register(function ($class_name) {
        $parts = explode('\\', $class_name);
        $file = array_pop($parts);
        $dir = implode('/', $parts) . '/';
        require_once __DIR__ . '/src/' . $dir . $file . ".php";
    });
}
