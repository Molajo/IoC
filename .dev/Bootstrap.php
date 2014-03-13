<?php
/**
 * Bootstrap for Testing
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
include_once __DIR__ . '/CreateClassMap.php';

if (! defined('PHP_VERSION_ID')) {
    $version = explode('.', phpversion());
    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

$base     = substr(__DIR__, 0, strlen(__DIR__) - 5);
$classmap = array();
$classmap = createClassMap($base . '/vendor/commonapi/ioc', 'CommonApi\\IoC\\');

$results  = createClassMap($base . '/vendor/commonapi/exception', 'CommonApi\\Exception\\');
$classmap = array_merge($classmap, $results);

$results  = createClassMap($base . '/.dev/Classes', 'Molajo\\');
$classmap = array_merge($classmap, $results);

$results  = createClassMap($base . '/.dev/Factories/CacheMock', 'Molajo\\Factories\\CacheMock\\');
$classmap = array_merge($classmap, $results);

$results  = createClassMap($base . '/.dev/Factories/ConfigurationMock', 'Molajo\\Factories\\ConfigurationMock\\');
$classmap = array_merge($classmap, $results);

$results  = createClassMap($base . '/Source/Api', 'Molajo\\IoC\\Api\\');
$classmap = array_merge($classmap, $results);

$classmap['Molajo\\IoC\\FactoryBase']           = $base . '/Source/FactoryBase.php';
$classmap['Molajo\\IoC\\Container']             = $base . '/Source/Container.php';
$classmap['Molajo\\IoC\\FactoryMethod']         = $base . '/Source/FactoryMethod.php';
$classmap['Molajo\\IoC\\Controller']            = $base . '/Source/Controller.php';
$classmap['Molajo\\IoC\\StandardFactoryMethod'] = $base . '/Source/StandardFactoryMethod.php';
ksort($classmap);

spl_autoload_register(
    function ($class) use ($classmap) {
        if (array_key_exists($class, $classmap)) {
            require_once $classmap[$class];
        }
    }
);
