# Molajo Inversion of Control (IoC)

The Molajo **Inversion of Control** package offers a full-featured, easy-to-use dependency injection solution for PHP applications.

## Features

- Structured services layer
    * Store custom dependency injection handlers for object construction
    * Supports 'lazy' or 'just in time' loading
    * Can be used in conjunction with bootup service instantiation

- Inversion of Control Container
    * Ability to pass in runtime variables with service request
    * Provides registry services for instantiated objects
    * Prevention of simultaneous instantiation requests for the same object

- Dependency Injection Handlers
    * Adapter pattern implementation for DI handlers
    * Standard DI handler for convention-based object construction
    * Custom DI handlers for complex object construction and dependency resolution
    * No limit per service
    * Support for constructor, setter and interface injection
    * Requests generated within the DI handler for object dependencies

- DI handler event methods include:
    * onBeforeServiceInstantiate
    * instantiate
    * instantiate_static
    * onAfterServiceInstantiate
    * initialise
    * onAfterServiceInitialise
    * getServiceInstance


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
     * @results  object
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

1. Service, required

This is either the class name or the fully qualified namespace
of the custom service DI handler or the class to be constructed.

2. Options associative array, not required

Typically, options in the array represent runtime variables.

Most of the time, no options are required.

The standard DI handler assumes runtime variables are to be injected into the object via the constructor. If such is not the case, a custom handler is required.

##### Example 1: No fully qualified namespace, no options array

When the fully qualified namespace is not defined for the service name, the Container tries to find it.

 First, it determines if there is a registered service for that name by prepending the services name provided
 with the registered Services namespace passed into the Container constructor.

 If not, it determines if the namespace Molajo\Service-name\Service-name is valid.

 If neither is a match,  the Container will throw an exception.

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
Anytime the Standard DI handler will be used, provide the following:
- Service Class Name
 - FQNS Service Class Namespace
 - Any of the following three items if the value of the item is true:
 $store_instance_indicator,  $static_instance_indicator, $store_properties_indicator,

#### Instantiation Options

There are three basic instantiation options. The default to each option is false. To override the default,
 the value can be set in the Custom DI Handler.

1. If a static instance is needed, set **static_instance_indicator** to true.

2. If the service object instance should be stored in the registry, set **store_instance_indicator** to true.

3. If the properties of the instantiated service object should be returned instead of the instance
(and/or stored within the registry if the previous response was true)
set **store_properties_indicator** to true.

**Exists**

For every getService request, the IoCC looks to see if the Service is already available.
If it is, the existing service is returned. (That service will only be saved if the answer to
1 or 2 above was true.)

**Does Not Exist, do not create new**

If the service instance is not already available, the IoCC will look
in the options for an entry named `if_exists`. The presence of that array item signals to the IoCC *not* to
create a new instance if an existing instance is not already available.

This is important to prevent a dependency
"standoff" where two services are both dependent on each other and neither instance exists. One example of that
is the database and user services. The user service requires the database to determine information. The database
instance uses the user object to process ACL decisions.

Careful consideration for how best to manage those
scenarios is important. In this case, the user instance, when not available is simply not used, thus allowing
the more critical database connection to first take place.

**Does not exist, create new**

If the service instance is not available and there is no `if_exists` options entry,
the IoCC will create a new instance. This enables lazy loading and resolving dependencies in the injector process.

### Dependency Injection

Finally, a discussion on dependency injection. First, both Constructor and Setter Dependency Injection are supported.
You may have noted, the examples did not instruct the IoCC which approach was preferred. To understand how
that decision is reached, it's important to look at how the **Standard** and **Custom** dependency injection handlers
function.

#### Injector Adapter

#### Standard Injector Handler

#### Custom Injector Handler
Custom Injector

###







### Service Locators vs IoC Containers

[Service Locator -vs- IoC Containers](http://i.msdn.microsoft.com/dynimg/IC355097.png)

Inversion of Control Containers (IoCC) are also called Dependency Injection Containers (DIC). A close
cousin is the **Service Locator.**  The difference between the two is how each are used.

A [Service Location](http://en.wikipedia.org/wiki/Service_locator_pattern) is used inside of an object,
'reaching out', as needed, to retrieve dependency objects and data.
[More on Service Locators](http://martinfowler.com/articles/injection.html#UsingAServiceLocator)...

An [Inversion of Control Container (IoCC)], also known as a [Dependency Injection Container (DIC),
satisfies dependencies 'outside' of the object, injecting objects and other required data into the object.



The problem with Service Locators is the dependency created on the concrete class due to the injection
of the Service Locator. To replace this dependency, you have to change the code. The code is more difficult
to test since it has diret references to dependencies. This likely results in more code for
object construction, dependency location, managing dependencies.

Instantiate your IoCC within the Container and do not inject it into other classes. Doing so turns
the IoCC into a Service Locator.











Instantiating a connection to a database can involve a significant number of small and moderately complex steps.

Using the Molajo IoCC, that code can be defined once in a custom injector handler and then invoked, each and every time the database connection is needed, using this simple command:

```php
$database = $this->iocc->getService('Database');
```
Behind the scenes, dependencies can trigger other *getService* requests, and those, more still, until all necessary elements of the Object Map are instantiated and available.

Using the Molajo IoCC, however, developers can be blissfully unaware of this processing since the process is managed for them.

## Molajo IoC Architecture
The Molajo IoC Package offers a full featured support structure, not just a Container class, but all of the elements needed to provide the logic and structure an application needs for dependency injection.

### Container
The [Container](https://github.com/Molajo/IoC/blob/master/Container.php) call and [Interface](https://github.com/Molajo/IoC/blob/master/Api/ContainerInterface.php) are the kernel of the IoC process.

The container determines if an stored instance is already available that would satisfy the request or if a new instance is needed.

If a new instance is needed, the **Container** instantiates a custom or the standard handler, and passes it into the **Injector Adapter** as it guides the Adapter through the event methods, passes in data, passes back results from this set of event methods: `onBeforeServiceInstantiate`, `instantiate`, `onAfterServiceInstantiate`, `initialize` and `onAfterServiceInitialise`; facilitate the process.

The **Container** class guards against multiple requests to instantiate the same service request, preventing difficult to debug looping issues before they happen.

### Injector Adapter
The **Container** interacts with the [Injector Adapter](https://github.com/Molajo/IoC/blob/master/Injector/Adapter.php) and [Adapter Interface](https://github.com/Molajo/IoC/blob/master/Api/AdapterInterface.php) when a new instance is needed.

The adapter interacts with the generic **Standard Injector** or a specific **Custom Injector** class, depending on the request.

The adapter responds to the container's requests, passing in data, handling the method response, as the container processes each of the methods from `onBeforeServiceInstantiate` to `onAfterServiceInitialise`.

### Injectors
The Injector classes do the heavy lifting. There are three types of Injectors: a **Custom Injector**, **Standard Injector**, and the **Abstract Injector**.

#### Standard Injector
The [Standard Injector](https://github.com/Molajo/IoC/blob/master/Injector/StandardInjector.php) extends the [Abstract Injector](https://github.com/Molajo/IoC/blob/master/Injector/AbstractInjector.php) are is typically sufficient to handle most instantiation requests. As the name suggests, the **Standard Injector** provides the default *configuration free* instantiation process.

Using passed in options, the **Standard Injector** can spawn other service dependent service requests, inject dependencies using the constructor, inject dependencies using class setters. No code is required using the **Standard Injector**.

#### Custom Injectors
[Custom Injectors](https://github.com/Molajo/IoC/blob/master/Injector/SampleInjector.php) also extend the [Abstract Injector](https://github.com/Molajo/IoC/blob/master/Injector/AbstractInjector.php) and are only necessary when the **Standard Injector** is not enough.

Creating a **Custom Injector** is pretty straightforward and very flexible. Normal PHP code can be written for each of the Injector event methods to fully manage complex dependency needs.  Multiple custom injectors can be defined for any class.

#### Abstract Injector [Interface]
The [Abstract Injector](https://github.com/Molajo/IoC/blob/master/Injector/AbstractInjector.php) implements the [InjectionInterface](https://github.com/Molajo/IoC/blob/master/Api/InjectorInterface.php). It is extended by both custom and standard injectors. As such, it contains the default code that is executed when the current injector handler does not override the abstract class method.

## Dependency Injection Approach
Given the architectural structure described in the previous section, dependency injection can be accomplished via the Constructor, the Setter, using an Interface, and invoked by an injector. The possibilities are very flexible.
### Constructor
The injector uses the `$options` array as instructions for managing constructor injection.
### Setter
In much the same manner, the `$setter_options` array is used to manage setter injection.
### Lazy loading of required classes
Regardless of whether the process uses an constructor or setter (or both) approach to injecting dependencies, many times, an Injector will issue a `getService` request for another class, supporting a lazy loading approach.
### Interface injection
A good object oriented practice is to have classes implement interfaces. This practice has many benefits, including clearly defining the application API, but also making it much easier to swap out one implementation of the Interface for another. The Molajo IoCC supports Interface injection.

### Type of Instance
The `getService` request can request one of four types of instances:

#### Request a new instance
This is done transparently by requesting a service where a like named service is not stored in the Container instance array.
#### Accept an available instance, if available, or create a new instance if it is not.
Special consideration of how the class object map loads is needed to use this option with confidence.
#### Accept an available instance, if available, or proceed without an instance if an existing instance does not exist.
The `$options['if_exists']` can be used to simply continue if an existing instance is not available. This option is required to situation where a class is waiting on another class, and visa versa.
#### Request a Singleton, or Static, Instance
As much as some might disagree, there are times when a `static instance` is required. Database connections should only be made one time due to performance implications and might be stored in a static instance for reuse. Static instances are defined using custom injector handlers where the property `$this->static_service_instance` is set to true in the constructor.

## Injector Properties and Methods
It is helpful to review the [Abstract Injector](https://github.com/Molajo/Standard/blob/master/Vendor/Molajo/IoC/Injector/AbstractInjector.php) to understand the properties and event methods that can be used for a custom injector (or are automatically processed, and how, for a standard injection).

Only normal PHP code is needed to write a custom injector, There is no special XML format to learn, or approach to setting cryptic rules. Just plain PHP code and a set of flexible event methods.

### Injector Properties

### Injector Event Methods

#### onBeforeServiceInstantiate

#### Instantiate
This is the event method where constructor injection happens and the class is instantiated.
#### instantiate_static
The Instantiate Static method does as the name suggests. Those injectors which set `$this->static_instance_indicator` to true invoke this code.
#### onAfterServiceInstantiate
After instantiation, setter injection can be defined in this event method using the data in $this->setter_options.
#### initialise
If the Service has an *initialise* method, it is invoked here,  following the service instantiation.
#### onAfterServiceInitialise
Follows Initialise, providing another opportunity for custom processing
#### getServiceInstance
This method reviews the values in these three properties:
* **$static_instance_indicator** set to true if a static instance is needed
* **$store_instance_indicator** Set to true if the instantiated instance is to be stored by the Container and returned for subsequent requests.
* **$store_properties_indicator** Set to true if only the data generated by the class is what should be sent back (or stored) by the Container.

In order to determine if the instance should be saved to be shared with other requests, if the instance was static, and therefore can be reused, or if the service results needed are the values returned by the request. Given that result, the appropriate result (an instance or return results) are sent back through the Adapter to the Container.

## Usage and examples
1. Bootstrapping (services.xml)
loop thru and call
2. Standard instantiation - no configuration required.
call - class, options constructor, options setter,
either the valid_properties array or reflection, what to do with the instance,
callbacks for each method in the options (Filtering?)
3. Services Library (custom configurations)
Simple
Object Graph - lazy loading - optional loading - think through your class object structure and purposefully design the instanatiation

Any class constructor which asks for an instance of a class that has been marked as shared will be passed the shared instance of the object rather than a new instance.


4. Overrides
5. Third-party


Examples:

IoCC Accessibility
frontcontroller

Roadmap
Considering adding YAML, JSON, XML-type configuration support that could be used instead of the Injector methods. Currently, feel the PHP approach is easier but it might be nice to have a choice conceivable would open some automation with ORM environments.

FAQ



**IoCC, DIC, and Service Locators are the mark of the beast. And, that beast is Martin Fowler.**

That is not a question.

**Will you add annotation or type hints for automated dependency injection?**

Heavens to Betsy, no. Doing so would be contrary to everything I believe about the danger of relying on comments in code.

** How is a Service Locator different than a IoCC/DIC?

Simple. A service locator is a IoCC/DIC that is injected into the class to resolve dependencies. Doing so makes the object
dependent upon the service locator. As with many things in life, we try to help but we just create problems.
