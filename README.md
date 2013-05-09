=======
Injection of Control Container
=======

[![Build Status](https://travis-ci.org/Molajo/IoC.png?branch=master)](https://travis-ci.org/Molajo/IoC)
Injection of Control Technical Description

Molajo's *Injection of Control Container* is useful to instantiate classes which provide application-wide services.
Service classes can be instantiated dynamically or statically using either (or both) constructor and setter dependency injection.
Multiple injectors can be defined per package. *Injectors* are custom configurations used
by the container to instantiate classes for defined services.

Examples of invoking services using the IoC are listed below. Note: the static call is due to the connection to the
Injection of Control Container, not to the service class instantiation, itself.

```php

// Retrieve a list of plugins defined for the onBeforeRead Event
$plugin_array = Services::Events()->get('Plugins', 'onBeforeRead');

// Obfuscate an Email address before rendering
$fieldValue = Services::Url()->obfuscateEmail($fieldValue);

// Add a debugging message to the Profiler
Services::Profiler()->set('message', 'Did this thing', 'Application' );

```

How to implement in your Application
--------------

***Installation

Add the Molajo DependencyInjection package to your install script.

```php

    "require": {
        "molajo/dependencyinjection": ">=1"
    }

```

***Create an Entry Point in your FrontController

Add a static property and a static method to your FrontController:

*Note:* use the name of your FrontController in place of the value *FrontController*. This is just an example.

```php

    /**
     * FrontController::Services
     *
     * @static
     * @var    object  Services
     * @since  1.0
     */
    protected static $services = null;

```

```php

    /**
     * FrontController::Services is accessed using Services::
     *
     * @param   null  $class
     *
     * @static
     * @return  null|object Services
     * @since   1.0
     * @throws  Exception
     */
    public static function Services($class = null)
    {
        if ($class === null) {
            $class = 'Molajo\\DependencyInjection\\Container';
        }

        if (static::$services) {
        } else {
            try {
                static::$services = new $class();

            } catch (FrontControllerException $e) {
                throw new FrontControllerException
                ('FrontController: Instantiate Injection of Control Container class Exception: ', $e->getMessage());
            }
        }

        return static::$services;
    }

```

Update the Molajo\DependencyInjection\Services Class to point to this new FrontController namespace and method.

```php

use Molajo\FrontController;

/**
 * Services
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class Services
{

    /**
     * Entry point for services called outside of the Services Class
     *
     * @static
     *
     * @param   string  $name
     * @param   array   $arguments
     *
     * @return  object
     * @since   1.0
     */
    public static function __callStatic($name, $arguments)
    {
        return FrontController::Services()->start($name);
    }

```

Services and Injectors
--------------
In order to use the Container, you must create *Injectors* and *Services.* A Service is the class that
the Container will instantiate. It could be FileServices, Filters, Email capability, and so on. Typically those
services needed anywhere in the Application. To define a Service and the configuration needed:

*** Define the Service

Create a subfolder beneath *Molajo\DependencyInjection\Services* that will become the name of the service
and copy the *Molajo\DependencyInjection\Services\Sample\SampleInjector.php* file into the folder.

├── Vendor
│   ├── Molajo
│   │   └── DependencyInjection
│   │       └── Cache
│   │       │   └── CacheInjector.php
│   │       └── Log
│   │       │    └── LogInjector.php
│   │       └── Sample
│   │           └── SampleInjector.php


*** Create an Injector for the Service

Copy the *Molajo\DependencyInjection\Services\Sample\SampleInjector.php* file into the folder naming it *Name-of-ServiceInjector*.
This new class
is modeled after the [Injector Class](https://github.com/Molajo/Standard/blob/master/Vendor/Molajo/DependencyInjection/Services/Injector.php)
and implements the [Injector Interface](https://github.com/Molajo/Standard/blob/master/Vendor/Molajo/DependencyInjection/Services/InjectorInterface.php).

Define the logic needed for the [Injector methods](https://github.com/Molajo/DependencyInjection/blob/master/Container.php#L148):

*Constructor*

The constructor defines three important parameters:

1. service_namespace - the location of the service class to be instantiated;
2. static_instance_indicator - true or false value indicating whether a static instance should be created (true) or not (false);
3. store_instance_indicator - true or false value indicating whether or not the instance should be stored (trued) or not stored (false), by the [`Molajo\DependencyInjection\Container`](https://github.com/Molajo/Standard/blob/master/Autoload.php#L73) and then shared when requested by a subsequent call;
4. store_properties_indicator - properties from the class process

```php

    public function __construct()
    {
        $this->service_namespace = 'Molajo\\Cache\\Adapter';
        $this->static_instance_indicator = false;
        $this->store_instance_indicator = true;
    }

```

*onBeforeServiceInstantiate*

Logic that should run before the Service is instantiated.

*instantiate*

The actual class instantiation. Add constructor dependency injection here, or if not needed, allow the parent class, *Injector*, handles it.

*onAfterServiceInstantiate*

Logic that is executed following the class instantiation. Any setter injection can be handled here.

*initialize*

Logic that runs the service class initialize method, if one exists.

*getServiceInstance*

Logic that returns the service instance to the [`Molajo\DependencyInjection\Container`](https://github.com/Molajo/Standard/blob/master/Autoload.php#L73)


Working with the Container and Using Services
--------------

Molajo's *Injection of Control Container* is accessed via a static call you defined above
 to the Front Controller Services method. That method then links to an instantiation of the Service class.

```php

Services::Service-Folder-Name()->services-method-name('parameters');

```
Examples of accessing services via the container look like the Service itself is static, but that is only
true if the configuration for the class so specified. The majority of the time, you will likely define
the configuration to be a dynamic instance.

The benefit in the application to such an approach is that all configuration can be pre-defined in one location
and the class instance created handled by the dependency injection container. Developers can easily work
with your application API with instance and dependencies managed within the application.

## System Requirements ##

* PHP 5.3.3, or above
* [PSR-0 compliant Autoloader](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
* PHP Framework independent
* [optional] PHPUnit 3.5+ to execute the test suite (phpunit --version)

### Installation

#### Install using Composer from Packagist

**Step 1** Install composer in your project:

```php
    curl -s https://getcomposer.org/installer | php
```

**Step 2** Create a **composer.json** file in your project root:

```php
{
    "require": {
        "Molajo/DependencyInjection": "1.*"
    }
}
```

**Step 3** Install via composer:

```php
    php composer.phar install
```

**Step 4** Add this line to your application’s **index.php** file:

```php
    require 'vendor/autoload.php';
```

This instructs PHP to use Composer’s autoloader for **DependencyInjection** project dependencies.

#### Or, Install Manually

Download and extract **DependencyInjection**.

Create a **Molajo** folder, and then a **DependencyInjection** subfolder in your **Vendor** directory.

Copy the **DependencyInjection** files directly into the **DependencyInjection** subfolder.

Register `Molajo\DependencyInjection\` subfolder in your autoload process.

About
=====

Molajo Project adopted the following:

 * [Semantic Versioning](http://semver.org/)
 * [PSR-0 Autoloader Interoperability](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
 * [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
 and [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
 * [phpDocumentor2] (https://github.com/phpDocumentor/phpDocumentor2)
 * [phpUnit Testing] (https://github.com/sebastianbergmann/phpunit)
 * [Travis Continuous Improvement] (https://travis-ci.org/profile/Molajo)
 * [Packagist] (https://packagist.org)


Submitting pull requests and features
------------------------------------

Pull requests [GitHub](https://github.com/Molajo/DependencyInjection/pulls)

Features [GitHub](https://github.com/Molajo/DependencyInjection/issues)

Author
------

Amy Stephen - <AmyStephen@gmail.com> - <http://twitter.com/AmyStephen><br />
See also the list of [contributors](https://github.com/Molajo/DependencyInjection/contributors) participating in this project.

License
-------

**Molajo DependencyInjection** is licensed under the MIT License - see the `LICENSE` file for details

More Information
----------------
- [Extend](https://github.com/Molajo/DependencyInjection/blob/master/.dev/Doc/extend.md)
- [Install](https://github.com/Molajo/DependencyInjection/blob/master/.dev/Doc/install.md)
