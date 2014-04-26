<?php
/**
 * Bootstrap for Testing
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
$base = substr(__DIR__, 0, strlen(__DIR__) - 5);
include_once $base . '/vendor/autoload.php';
include_once __DIR__ . '/CreateClassMap.php';

$classmap['Molajo\\IoC\\Container']               = $base . '/Source/Container.php';
$classmap['Molajo\\IoC\\FactoryMethodBase']       = $base . '/Source/FactoryMethodBase.php';
$classmap['Molajo\\IoC\\FactoryMethodController'] = $base . '/Source/FactoryMethodController.php';
$classmap['Molajo\\IoC\\MapFactories']            = $base . '/Source/MapFactories.php';
$classmap['Molajo\\IoC\\Schedule']                = $base . '/Source/Schedule.php';
$classmap['Molajo\\IoC\\StandardFactoryMethod']   = $base . '/Source/StandardFactoryMethod.php';

spl_autoload_register(
    function ($class) use ($classmap) {
        if (array_key_exists($class, $classmap)) {
            require_once $classmap[$class];
        }
    }
);
