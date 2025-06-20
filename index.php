<?php

use lib\Init;

function autoload($className) {
    $filepath = str_replace('\\', '/', $className) . '.php';

    if (is_file($filepath)) {
        include $filepath;
    }
}

spl_autoload_register('autoload');

$controller = 'index';

$action = 'index';

$params = [];

if (isset($_SERVER['PATH_INFO'])) {
    $params = explode('/', $_SERVER['PATH_INFO']);

    unset($params[0]);

    if ($params[1] && class_exists('controllers\\' . $params[1])) {
        $controller = $params[1];

        unset($params[1]);
    }

    if (isset($params[2]) && method_exists('controllers\\' . $controller, $params[2])) {
        $action = $params[2];

        unset($params[2]);
    }
}

Init::init();

echo call_user_func_array("controllers\\$controller::$action", $params);
