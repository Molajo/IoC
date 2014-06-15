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


$classmap['Molajo\\IoC\\FactoryMethod\\Adapter']     = $base . '/Source/FactoryMethod/Adapter.php';
$classmap['Molajo\\IoC\\FactoryMethod\\Base']        = $base . '/Source/FactoryMethod/Base.php';
$classmap['Molajo\\IoC\\FactoryMethod\\Controller']  = $base . '/Source/FactoryMethod/Controller.php';
$classmap['Molajo\\IoC\\FactoryMethod\\Instantiate'] = $base . '/Source/FactoryMethod/Instantiate.php';
$classmap['Molajo\\IoC\\FactoryMethod\\Standard']    = $base . '/Source/FactoryMethod/Standard.php';

$classmap['Molajo\\IoC\\Product\\Create']            = $base . '/Source/Product/Create.php';
$classmap['Molajo\\IoC\\Product\\ClassDependencies'] = $base . '/Source/Product/ClassDependencies.php';
$classmap['Molajo\\IoC\\Product\\SetNamespace']      = $base . '/Source/Product/SetNamespace.php';

$classmap['Molajo\\IoC\\Schedule\\Base']       = $base . '/Source/Schedule/Base.php';
$classmap['Molajo\\IoC\\Schedule\\Create']     = $base . '/Source/Schedule/Create.php';
$classmap['Molajo\\IoC\\Schedule\\Dependency'] = $base . '/Source/Schedule/Dependency.php';
$classmap['Molajo\\IoC\\Schedule\\Request']    = $base . '/Source/Schedule/Request.php';

$classmap['Molajo\\IoC\\Schedule\\Container']    = $base . '/Source/Container.php';
$classmap['Molajo\\IoC\\Schedule\\MapFactories'] = $base . '/Source/MapFactories.php';
$classmap['Molajo\\IoC\\Schedule\\Schedule']     = $base . '/Source/Schedule.php';

$classmap['Molajo\\Event\\Dispatcher']
    = $base . '/.dev/Tests/Files/Event/Dispatcher.php';
$classmap['Molajo\\Event\\EventDispatcher']
    = $base . '/.dev/Tests/Files/Event/EventDispatcher.php';
$classmap['Molajo\\Factories\\Dispatcher\\DispatcherFactoryMethod']
    = $base . '/.dev/Tests/Files/Factories/Dispatcher/DispatcherFactoryMethod.php';
$classmap['Molajo\\Factories\\DispatcherDog\\DispatcherDogFactoryMethod']
    = $base . '/.dev/Tests/Files/Factories/DispatcherDog/DispatcherDogFactoryMethod.php';

spl_autoload_register(
    function ($class) use ($classmap) {
        if (array_key_exists($class, $classmap)) {
            require_once $classmap[$class];
        }
    }
);

