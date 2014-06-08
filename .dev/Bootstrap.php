<?php
/**
 * Bootstrap for Testing
 *
 * @package    Molajo
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
$base = substr(__DIR__, 0, strlen(__DIR__) - 5);
if (function_exists('CreateClassMap')) {
} else {
    include_once __DIR__ . '/CreateClassMap.php';
}
include_once $base . '/vendor/autoload.php';

$classmap['Molajo\\IoC\\Container']                = $base . '/Source/Container.php';
$classmap['Molajo\\IoC\\FactoryMethod\\Adapter']     = $base . '/Source/Adapter.php';
$classmap['Molajo\\IoC\\FactoryMethod\\Instantiate'] = $base . '/Source/Instantiate.php';
$classmap['Molajo\\IoC\\FactoryMethod\\Base']        = $base . '/Source/Base.php';
$classmap['Molajo\\IoC\\FactoryMethod\\Controller']  = $base . '/Source/Controller.php';
$classmap['Molajo\\IoC\\Create']      = $base . '/Source/Create.php';
$classmap['Molajo\\IoC\\Namespace']                = $base . '/Source/Namespace.php';
$classmap['Molajo\\IoC\\MapFactories']             = $base . '/Source/MapFactories.php';
$classmap['Molajo\\IoC\\Schedule']                 = $base . '/Source/Schedule.php';
$classmap['Molajo\\IoC\\FactoryMethod\\Standard']    = $base . '/Source/Standard.php';

$classmap['Molajo\\Event\\Dispatcher']
    = $base . '/.dev/Tests/Files/Event/Dispatcher.php';
$classmap['Molajo\\Event\\EventDispatcher']
    = $base . '/.dev/Tests/Files/Event/EventDispatcher.php';
$classmap['Molajo\\Factories\\Dispatcher\\DispatcherFactoryMethod']
    = $base . '/.dev/Tests/Files/Factories/Dispatcher/DispatcherFactoryMethod.php';
$classmap['Molajo\\Factories\\Dispatcher\\DispatcherDogFactoryMethod']
    = $base . '/.dev/Tests/Files/Factories/Dispatcher/DispatcherDogFactoryMethod.php';

spl_autoload_register(
    function ($class) use ($classmap) {
        if (array_key_exists($class, $classmap)) {
            require_once $classmap[$class];
        }
    }
);
