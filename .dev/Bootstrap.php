<?php
/**
 * Inversion of Control
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */


if (substr($_SERVER['DOCUMENT_ROOT'], - 1) == '/') {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT']);
} else {
    define('ROOT_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/');
}

$base = substr(__IoCR__, 0, strlen(__IoCR__) - 5);
define('BASE_FOLDER', $base);

$classMap = array(
    'Molajo\\IoC\\Container'                     => BASE_FOLDER . '/Container.php',
    'Molajo\\IoC\\Injector'                      => BASE_FOLDER . '/Services/Injector.php',
    'Molajo\\IoC\\Exception\\ContainerException' => BASE_FOLDER . '/Exception/ContainerException.php',
    'Molajo\\IoC\\Exception\\InjectorException'  => BASE_FOLDER . '/Exception/InjectorException.php',
    'Molajo\\IoC\\Api\\ContainerInterface'       => BASE_FOLDER . '/Api/ContainerInterface.php',
    'Molajo\\IoC\\Api\\ExceptionInterface'       => BASE_FOLDER . '/Api/ExceptionInterface.php',
    'Molajo\\IoC\\Api\\InjectorInterface'        => BASE_FOLDER . '/Api/InjectorInterface.php'
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
