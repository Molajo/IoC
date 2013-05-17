<?php
/**
 * Inversion of Control
 *
 * @package   Molajo
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
$base = substr(__DIR__, 0, strlen(__DIR__) - 5);

$classMap = array(
    'Molajo\\IoC\\Api\\ContainerInterface'                   => $base . '/Api/ContainerInterface.php',
    'Molajo\\IoC\\Api\\ExceptionInterface'                   => $base . '/Api/ExceptionInterface.php',
    'Molajo\\IoC\\Api\\InjectorInterface'                    => $base . '/Api/InjectorInterface.php',
    'Molajo\\IoC\\Exception\\AdapterException'               => $base . '/Exception/AdapterException.php',
    'Molajo\\IoC\\Exception\\ContainerException'             => $base . '/Exception/ContainerException.php',
    'Molajo\\IoC\\Exception\\InjectorException'              => $base . '/Exception/InjectorException.php',
    'Molajo\\IoC\\Handler\\AbstractInjector'                 => $base . '/Handler/AbstractInjector.php',
    'Molajo\\IoC\\Handler\\CustomInjector'                   => $base . '/Handler/CustomInjector.php',
    'Molajo\\IoC\\Handler\\StandardInjector'                 => $base . '/Handler/StandardInjector.php',
    'Molajo\\IoC\\Adapter'                                   => $base . '/Adapter.php',
    'Molajo\\IoC\\Container'                                 => $base . '/Container.php',
    'Molajo\\Cache'                                          => $base . '/.dev/Classes/Cache.php',
    'Molajo\\Configuration'                                  => $base . '/.dev/Classes/Configuration.php',
    'Molajo\\Services\\Cache\\CacheInjector'                 => $base . '/.dev/Services/Cache/CacheInjector.php',
    'Molajo\\Services\\Configuration\\ConfigurationInjector' => $base . '/.dev/Services/Configuration/ConfigurationInjector.php',
);

spl_autoload_register(
    function ($class) use ($classMap) {
        if (array_key_exists($class, $classMap)) {
            require_once $classMap[$class];
        }
    }
);
