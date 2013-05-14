# Molajo Inversion of Control (IoC)

The Molajo **Inversion of Control** package offers a full-featured,
 dependency injection solution and a services layer for PHP applications.

[Molajo Inversion of Control (IoC)](https://github.com/Molajo/IoC/blob/master/.dev/IoC.png)
## Features

### Services Layer
* Repository of custom dependency injection handlers used during object construction - [see Molajo Services as an example](https://github.com/Molajo/Standard/tree/master/Vendor/Molajo/Services)
* Supports 'lazy' or 'just in time' loading
* Can be used in conjunction with [bootup service instantiation](https://github.com/Molajo/Standard/blob/master/Vendor/Molajo/Application/FrontController.php#L235)
* Application API

### Inversion of Control Container (IoCC)
* Ability to pass in runtime variables with service request
* Provides registry services for instantiated objects
* Prevention of simultaneous instantiation requests for the same object

### Dependency Injection (DI) Handlers
* [Adapter pattern](http://en.wikipedia.org/wiki/Adapter_pattern) implementation for DI handlers
* [Standard DI handler](https://github.com/Molajo/IoC/blob/master/Handler/StandardInjector.php) for convention-based object construction
* [Custom DI handlers](https://github.com/Molajo/IoC/blob/master/Handler/CustomInjector.php) for complex object construction and dependency resolution
* Unlimited custom DI handlers per service
* Support for constructor, setter and interface injection
* The DI handler automatically generates getService requests for object dependencies

### DI handler event methods include (links to AbstractHandler methods):
* [onBeforeServiceInstantiate](https://github.com/Molajo/IoC/blob/master/Handler/AbstractInjector.php#L200)
* [instantiate](https://github.com/Molajo/IoC/blob/master/Handler/AbstractInjector.php#L214)
* [onAfterServiceInstantiate](https://github.com/Molajo/IoC/blob/master/Handler/AbstractInjector.php#L267)
* [initialise](https://github.com/Molajo/IoC/blob/master/Handler/AbstractInjector.php#L279)
* [onAfterServiceInitialise](https://github.com/Molajo/IoC/blob/master/Handler/AbstractInjector.php#L306)
* [getServiceInstance](https://github.com/Molajo/IoC/blob/master/Handler/AbstractInjector.php#L318)

## Front Controller

In this example, the IoC [Container](https://github.com/Molajo/IoC/blob/master/Container.php) /
[Interface](https://github.com/Molajo/IoC/blob/master/Api/ContainerInterface.php) is instantiated within the
 [Front Controller](http://www.martinfowler.com/eaaCatalog/frontController.html) Initialisation method and
  the connection is stored in a class property.

```php
    /**
     * Inversion of Control Container
     *
     * @var    object
     * @since  1.0
     */
    protected $iocc;
```

When the container object is instantiated, the namespace for the
 default service_library can be provided. This is the centralized location
 where custom DI handlers are stored.

If the service_library setting is not provided, it defaults to Molajo\Services.

Each getService request can specify the fully qualified namespace of the service requested, thus
overriding this default setting.

```php
    $class = 'Molajo\\IoC\\Container';
    $service_library = 'Molajo\\Services';

    $this->iocc  = new $class($service_library);
```

### getService Method

The job of the Front Controller is to handle requests. Requests for services are made of
 the IoC container which, in turn, interacts with the DI Adapter and the appropriate DI handler
 to satisfy object dependencies, construct the object and optionally register the object in the
  service registry.

```php

    /**
     * Service Requests for the Inversion of Control Container
     *
     * @param    string $service
     * @param    array  $options
     *
     * @return  object
     * @since    1.0
     * @throws   FrontControllerException
     */
    public function getService($service, $options = array())
    {
        return $this->iocc->getService($service, $options);
    }
```

#### getService Request

There are two parameters for the getService method:

##### Service, required

This is either the class name or the fully qualified namespace
of the custom service DI handler or the class to be constructed.

##### Options associative array, not required

Typically, options in the array represent runtime variables. Most of the time, no options are required.

For every getService request, the IoCC looks to see if the Service is already available.
If it is, the existing service is returned. If it does not existing, a new instance will be created unless
the `if_exists` $options entry is available. This is
useful in those situations two services are dependent on each other
and neither instance exists.

##### Example 1: No fully qualified namespace, no options array

When the fully qualified namespace is not defined for the service name, the Container looks in the Service Library
 defined during the Container instantiation. It it does not exist, the Standard DI Handler is used.

```php
// Retrieve Database Connection
$database = $this->iocc->getService('Database');

```
##### Example 2: Fully qualified namespace and options array

In this second example, a `getService` request is issued for the User object where the UserID is 1000.

```php
// Retrieve User Object
$options = array;
$options['UserID'] = 1000;
$database = $this->iocc->getService('Molajo\\User', $options);

```

## Construction and Dependency Injection

### Container

The [Container](https://github.com/Molajo/IoC/blob/master/Container.php) call
and [Interface](https://github.com/Molajo/IoC/blob/master/Api/ContainerInterface.php) are the kernel of the IoC process.

The container determines if a stored instance is available or if a new instance is needed.

If the instance is not available and the `if_exists` $options entry was specified, the process returns without an instance.

If a new instance is needed, the IoC Container instantiates either a Custom or Standard DI Handler
 and injects it into the
[Injector Adapter](https://github.com/Molajo/IoC/blob/master/Injector/Adapter.php), executing each of the
adapter event methods.

### Adapters and Handlers ###

The Adapter is injected with
a [Custom Injectors](https://github.com/Molajo/IoC/blob/master/Handler/CustomInjector.php) or
[Standard Injector](https://github.com/Molajo/IoC/blob/master/Handler/StandardInjector.php), both
extend the [Abstract Injector](https://github.com/Molajo/IoC/blob/master/Handler/AbstractInjector.php),

The event methods for any DI Handler are: onBeforeServiceInstantiate, Instantiate, onAfterServiceInstantiate, initialise, onAfterServiceInitialise, and getServiceInstance.

#### Standard Injector Handler

The standard DI handler is uses Reflection to determine constructor and setter methods and parameters, matching
those to the $options array, using these dependencies to instantiate the class for the service requested.

#### Custom Injector Handler

In the class constructor, set the instantiation options that must be true. (The default to each option is false.)

1. If a static instance is needed, set **$this->static_instance_indicator** to true.

2. If the service object instance should be stored in the registry, set **$this->store_instance_indicator** to true.

3. If the properties of the instantiated service object should be returned instead of the instance
(and/or stored within the registry if the previous response was true)
set **$this->store_properties_indicator** to true.

#### Custom Code in Event Methods

Custom code can be added to any of the event methods or allowed to use the parent Abstract Handler method.
