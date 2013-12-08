=======
Inversion of Control (IoC) Package
=======

[![Build Status](https://travis-ci.org/Molajo/IoC.png?branch=master)](https://travis-ci.org/Molajo/IoC)

The [Molajo Inversion of Control (IoC)](https://github.com/Molajo/IoC/blob/master/.dev/IoC.png)
    package offers a full-featured, dependency injection solution and a services layer for PHP applications.

## Usage
These are the basic steps to implementing the Inversion of Control (IoC) package:

1. Create a [Service Folder](https://github.com/Molajo/IoC#1-service-folder) to store custom dependency injection handlers.
2. Update the [Front Controller](https://github.com/Molajo/IoC#2-front-controller) for the Inversion of Control Container (IoCC).
3. [Request Services](https://github.com/Molajo/IoC#3-application-service-requests) from the IoCC within the Application.
4. Create [Custom Service Providers](https://github.com/Molajo/IoC#4---custom-dependency-injection-handlers) for Services.

### 1. Service Folder

Create a folder within your application to store Service DI Handlers. Each Customer Handler has a folder
containing a class file named `ClassnameServiceProvider.php`. When instantiating the IoCC, you'll provide
the namespace of the Services Folder.

```
Molajo
.. Services
.. .. Database
.. .. .. DatabaseServiceProvider.php
.. .. Configuration
.. .. .. ConfigurationServiceProvider.php
.. .. Modelread
.. .. .. ModelreadServiceProvider.php
.. .. etc ..

```
The default namespace for a services folder is `Molajo\Service`. Whatever value is used, it will
be passed in as a parameter when instantiating the Container class.

### 2. Front Controller

The [Front Controller](http://www.martinfowler.com/eaaCatalog/frontController.html) should be the only
point of entry into the Inversion of Control Container (IoCC).

#### Use Statement

Add a use statement for the IoC Container class.

```php
use Molajo\IoC\Container;

```

#### Class Property

Define a class property in which to store the IoCC instance.

```php
    /**
     * Inversion of Control Container
     *
     * @var    object
     * @since  1.0
     */
    protected $iocc;

```

#### IoCC Methods

Add these four methods: getService, setService, cloneService, and removeService to the Front Controller.

```php

    /**
     * Get a service instance
     *
     * @param    string $service
     * @param    array  $options
     *
     * @results  null|object
     * @since    1.0
     * @throws   FrontcontrollerException
     */
    public function getService($service, $options = array())
    {
        return $this->ioc->getService($service, $options);
    }

    /**
     * Replace the existing service instance
     *
     * @param    string $service
     * @param    object $instance
     *
     * @results  $this
     * @since    1.0
     * @throws   FrontcontrollerException
     */
    public function setService($service, $instance = null)
    {
        $this->ioc->getService($service, $instance);

        return $this;
    }

    /**
     * Clone the existing service instance
     *
     * @param    string $service
     *
     * @results  null|object
     * @since    1.0
     * @throws   FrontcontrollerException
     */
    public function cloneService($service)
    {
        return $this->ioc->cloneService($service);
    }

    /**
     * Remove the existing service instance
     *
     * @param    string $service
     *
     * @results  $this
     * @since    1.0
     */
    public function removeService($service)
    {
        $this->ioc->removeService($service);

        return $this;
    }
```

#### Instantiate the Container

In the Front Controller boot process, instantiate the [Container](https://github.com/Molajo/IoC/blob/master/Container.php)
using the code that begins with **$connect** and ends before  "// Automatically Load These Services". The four closure
statements passed into the Container will be used outside of the Front Controller to access the IoCC methods
    defined in the previous step. Note, also, the location of the $services_folder is passed into the Container.

```php

    /**
     * Initialise Application, including invoking Inversion of Control Container and
     *  Services defined in Services.xml
     *
     * @return  $this
     * @since   1.0
     * @throws  FrontcontrollerException
     */
    public function initialise()
    {
        $this->checkPHPMinimum();

        set_exception_handler(array($this, 'handleException'));
        set_error_handler(array($this, 'handlePHPErrors'), 0);

        $connect       = $this;
        $getService    = function ($service, $options = array()) use ($connect) {
            return $connect->getService($service, $options);
        };
        $setService    = function ($service, $instance) use ($connect) {
            return $connect->setService($service, $instance);
        };
        $cloneService  = function ($service) use ($connect) {
            return $connect->cloneService($service);
        };
        $removeService = function ($service) use ($connect) {
            return $connect->removeService($service);
        };

        $services_folder = 'Molajo\\Service';

        $this->ioc = new Container($getService, $setService, $cloneService, $removeService, $services_folder);

        // Automatically Load These Services
        $xml_string = $this->readXMLFile(__DIR__ . '/' . 'Services.xml');

        $services = simplexml_load_string($xml_string);

        foreach ($services->service as $service) {
            $this->getService((string)$service->attributes()->name, array());
        }

        return;
    }

```

### 3. Application Service Requests

The four closures provide a way to access the Front Controller entry points into the IoCC.

- **$getService** - create the $service using the $options specified, or return an existing service with the same
name;
- **$setService** - replace the existing $service registry in the container with the instance specified;
- **$cloneService** - clone the container registry entry for a specified service, returning the cloned instance;
- **$removeService** - remove the container registry entry specified;

When the IoC Container creates a DI Handler instance, it injects it with all four closures before injecting
the handler into the DI Adapter. The handler can use those class properties to interact with the IoCC.
In this example, the handler requests the dependent Application Service.

```php

    $getService = $this->getService;
    $application = $getService('Application');

    /** Has cache been activated? */
    $cache_service = $application->get('cache_service');
    if ((int)$cache_service === 0) {
        return $this;
    }

```

#### getService Parameters

1. **$service** Fully qualified namespace (ex. `Molajo\\Service\\Database\\DatabaseInjector`) or
the name of the Services sub-folder (ex. `Database`).
2. **$options** Optional associative array contain runtime parameters required by the service.

#### Existing, If Exists, or New Instance

When the Container processes a getService request, it first determines if the named service exists in the
registry. If it is, the existing service is returned. If it does not exist, a new instance will be created unless
the `if_exists` $options entry was specified in the request.


#### Example 1: No fully qualified namespace

When the fully qualified namespace is not defined, the Container looks for a folder with that name
in the Services library. In this example, the $options array defines runtime variables for the
instantiation process.

```php
$getService = $this->getService;

$options = array;
$options['model_type'] = 'Application';
$options['model_name'] = 'Includers';
$database = $getService('LocateFile', $options);

```

#### Example 2: if_exists Option

This request instructs the Container only return an instance of the User if that instance already exists.

```php
$getService = $this->getService;

$options = array;
$options['if_exists'] = true;
$database = $getService('Molajo\\User', $options);

```

#### Example 3: Standard DI Handler

A Service DI Handler is not always needed. Classes can be created by the Standard DI Handler. It will match
the values defined in the $options associative array with the Constructor parameters and use that data
to create the class. For the service name, use the fully qualified class name.

```php
$getService = $this->getService;

$options = array;
$options['constructor_parameter1'] = true;
$options['constructor_parameter2'] = true;
$database = $getService('Molajo\\Utilities\\Classname', $options);

```

#### setService

Replaces the existing Container registry entry for this service with the value sent in.

```php
$setService = $this->setService;
$database = $setService('Application', $instance);

```

#### cloneService

Clones the existing Container registry entry for this service and returns the cloned value.

```php
$cloneService = $this->cloneService;
$database = $cloneService('Database');

```

#### removeService

Removes the existing Container registry entry for this service.

```php
$removeService = $this->removeService;
$database = $removeService('Database');

```

### 4 - Custom Service Providers

To create a Custom Dependency Injection Handler:

1. Add a folder to the **Services Folder** defined in [Step 1](https://github.com/Molajo/IoC#1-service-folder). The folder name is the name of the service.
2. Create a PHP file in that folder named `ServiceNameServiceProvider.php`.


```
Molajo
.. Services
.. .. ServiceName
.. .. .. ServiceNameServiceProvider.php
```

#### Standard Properties

The Custom DI Handler has access to the following class properties:

1. **$getService** - Closure to request a service of the IoCC, defined above
2. **$setService** - Closure to set a service contained within the IoCC registry, defined above
3. **$cloneService** - Closure to clone a service contained within the IoCC registry, defined above
4. **$removeService** - Closure to remove a service contained within the the IoCC registry, defined above
5. **$service** - The name specified in the getService statement
6. **$service_namespace** - Set the fully qualified namespace for the Class to be instantiated in the constructor
8. **$store_instance_indicator** - defaults to false, set to true  in the constructor to store the instance in the IoCC registry
7. **$static_instance_indicator** - defaults to false, set to true in the constructor to request a static instance
7. **$store_properties_indicator** - defaults to false, set to true in the constructor to store the properties, not the instance, in the IoCC registry
9. **$service_instance** - populated with the instantiated class
10. **$options** - associative array provided by the getService call

#### Custom Service Provider Starter

Below is a basic starting pattern for a Custom Dependency Injection Handler.
The event methods available to the DI Handler are: processFulfilledDependencies, Instantiate, performAfterInstantiationLogic,
initialise, onAfterServiceInitialise, and getServiceInstance.
Each method can be used to inject code at different points in the class creation process. The
like-named [AbstractHandler](https://github.com/Molajo/IoC/blob/master/Handler/AbstractServiceProvider.php)
method will be used for any omitted methods in the custom class.  It is a good idea to become familiar with that class.

The [Molajo\Service](https://github.com/Molajo/Standard/tree/master/Vendor/Molajo/Services)
folder is also a good source of examples of Custom DI Injectors.

```php
<?php
/**
 * Example Custom Dependency Injection Handler
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Service\Example;

use Molajo\IoC\AbstractServiceProvider;
use CommonApi\IoC\ServiceProviderInterface;
use CommonApi\Exception\RuntimeException;

/**
 * Example Custom Dependency Injection Handler
 *
 * @author    Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ExampleServiceProvider extends AbstractServiceProvider implements ServiceProviderInterface
{
    /**
     * Constructor
     *
     * @param   $options
     *
     * @since   1.0
     */
    public function __construct(array $options = array())
    {

        $this->service_namespace            = 'Molajo\\Example\\Classname';

        // These default to false - set to true here if needed
        $this->store_instance_indicator     = false;
        $this->static_instance_indicator    = false;
        $this->store_properties_indicator   = false;

        parent::__construct($options);
    }

    /**
     * Follows instantiation of the service class and before the method identified as the "start" method
     *
     * @return  object
     * @since   1.0
     */
    public funprocessFulfilledDependenciestances()
    {
        retprocessFulfilledDependenciesncyInstances();
    }

    /**
     * Instantiate Class
     *
     * @param   bool $create_static
     *
     * @return  $this
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function instantiateService()
    {
        return parent::instantiate($create_static);
    }

    /**
     * Follows the completion of the instantiate service method
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function performAfterInstantiationLogic()
    {
        return parent::performAfterInstantiationLogic();
    }

    /**
     * Initialise Service Class, if the method exists
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function initialise()
    {
        return parent::initialise();
    }

    /**
     * Follows the completion of Initialise
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function onAfterServiceInitialise()
    {
        return parent::onAfterServiceInitialise();
    }

    /**
     * Get Service Instance
     *
     * @return  object
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException;
     */
    public function getServiceInstance()
    {
        return parent::getServiceInstance();
    }
}


```


## Install using Composer from Packagist ##

### Step 1: Install composer in your project ###

```php
    curl -s https://getcomposer.org/installer | php
```

### Step 2: Create a **composer.json** file in your project root ###

```php
{
    "require": {
        "Molajo/Ioc": "1.*"
    }
}
```

### Step 3: Install via composer ###

```php
    php composer.phar install
```

## Requirements and Compliance ##
 * PHP framework independent, no dependencies
 * Requires PHP 5.3, or above
 * [Semantic Versioning](http://semver.org/)
 * Compliant with:
    * [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) and [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md) Namespacing
    * [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) Coding Standards
 * [phpDocumentor2] (https://github.com/phpDocumentor/phpDocumentor2)
 * [phpUnit Testing] (https://github.com/sebastianbergmann/phpunit)
 * [Travis Continuous Improvement] (https://travis-ci.org/profile/Molajo)
 * Listed on [Packagist] (http://packagist.org) and installed using [Composer] (http://getcomposer.org/)
 * Use github to submit [pull requests](https://github.com/Molajo/Ioc/pulls) and [features](https://github.com/Molajo/Ioc/issues)
 * Author [Amy Stephen](http://twitter.com/AmyStephen)
 * [MIT License](http://opensource.org/licenses/MIT) see the `LICENSE` file for details
