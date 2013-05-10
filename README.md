Molajo Inversion of Control
===========================

The Molajo **Inversion of Control** package offers a full-featured dependency injection solution for PHP applications.

## Features

- Facilitates new, existing, cloned, and static instantiation
- Supports constructor and setter injection
- Standard (no code) injection handler sufficient for most requests
- Custom injector handlers can be developed using normal PHP code for more complex needs
- Triggered instantiation for discovered dependencies
- Lazy loading of objects, prompted only when needed

## Instantiating the *Inversion of Control Container*



```php
    $class = 'Molajo\\IoC\\Container';
    $this->iocc  = new Container();

```

#### Front Controller

This is an example of how to implement the IoCC within the application front controller. It's not the
only approach that can be used and it might not be the best choice, depending on your application. The
example is useful in defining approach that must be followed to use the IoCC in your application.

##### Instantiate the Inversion of Control Container

Within the bootstrap or front controller, instantiate the Inversion of Control
[Container](https://github.com/Molajo/IoC/blob/master/Container.php)
[Interface](https://github.com/Molajo/IoC/blob/master/Api/ContainerInterface.php)
 and store the instance in a class property.

```php
    /**
     * Inversion of Control Container
     *
     * @var    object
     * @since  1.0
     */
    protected $iocc;
```

```php

    $class = 'Molajo\\IoC\\Container';
    $this->iocc  = new Container();

```

##### Get Service Method

Add a front controller method to request services of the IoCC, passing back results.

```php

    /**
     * Route Service Requests to the Inversion of Control Container
     *
     * This method ensures a connection within the Front Controller to the IoCC
     *
     * @param    string $service_name
     * @param    array  $options
     *
     * @results  null|object
     * @since    1.0
     * @throws   FrontControllerException
     */
    public function getService($service_name, $options = array())
    {
        return $this->iocc->getService($service_name, $options = array());
    }

```

##### Get Service
The syntax to obtain an instantiated service from the IoCC is very simple. Identify the Service desired and pass
in options needed by the dependency injector.

**Custom Dependency Injector**
The values passed in using the $options array will vary. In some cases,
as this example shows, no options are required. Of course, the database connection will need quite a bit of
information for instantiation but a custom injector is in place to manage the configuration interaction.

```php
// Retrieve Database Connection
$database = $this->iocc->getService('Database');
```

**Standard Dependency Injector**
In this second example, a `getService` request is made of the IoCC and an ID is passed in identifying the key
value for that user. In this case, the key value is all that is needed to instantiate the class so the namespace
is passed in as a second element of the $options array. The Standard dependency injector handles these
basic requests, no custom dependency injector is required.

```php
// Retrieve User Object
$options = array;
$options['id'] = $this->row->id;
$options['namespace'] = 'Molajo\\User';
$database = $this->iocc->getService('User', $options);

```

**Application Startup**

In this example, the front controller is the first class instantiated, which is accomplished within the
index.php boot process. One of the first tasks the front controller must accomplish is loading basic
services. Following is an example intended to describe the basic principles for defining
application service requests during initialisation processes.

As you can see, an xml file containing the names of the services is read and a getService request
is issued, one at a time, until all basic services are up and running. From that point on, services
can be requested when needed.

```php

    /**
     * Initialise Application, including invoking Inversion of Control Container and
     *  Services defined in Services.xml
     *
     * @return  $this
     * @since   1.0
     * @throws  FrontControllerException
     * @api
     */
    public function initialise()
    {
        // ... snip ...

        $this->iocc  = new Container();

        $xml_string = $this->readXMLFile(__DIR__ . '/' . 'Services.xml');

        $services   = simplexml_load_string($xml_string);

        foreach ($services->service as $service) {
            $this->getService((string)$service->attributes()->name, array());
        }

        return;
    }

```

```xml
    <?xml version="1.0" encoding="utf-8"?>
    <services>
        <service name="Registry"/>
        <service name="Site"/>
        <service name="Application"/>
        <service name="Permissions"/>
        <service name="User"/>
        <service name="Language"/>
        <service name="Date"/>
    </services>

```
No, you do not have to use XML.  Yes, I know many people do not like it.  It is just an example.


```

#### Instantiation Type

As you might have observed from the previous examples, there was no explicit request for a new or
existing instance. For the most part, that is handled transparently but it is helpful to understand how.

When a service is instantiated, three basic instantiation questions are answered (if not answered explicitly by
including those elements in the $options array or defining the values in a custom dependency injector,
the default answer is assumed to be false for each question.):

1. Is a *Static Instance* needed? If so, **$static_instance_indicator** is set to true.

2. Should the instance be stored and shared for subsequent **getService** requests for the same Service? If so,
set the **$store_instance_indicator** to true and the **Container** will store and share the instance.

3. If the two previous are false, should the properties of the instantiated class be stored and shared for
subsequent **getService** requests? If this is needed, set the **$store_properties_indicator**
to true.

Given those answers the IoCC stores the instance, the static instance, the properties or nothing.

**Exists**

For every getService request, the IoCC looks to see if the Service is already available.
If it is, the existing service is returned. (That service will only be saved if the answer to
1 or 2 above was true.)

**Does Not Exist, do not create new **

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
that decision is reached, it's important to look at how the **Standard* and **Custom** dependency injection handlers
process requests.

#### Injector Adapter

#### Standard Injector Handler

#### Custom Injector Hander
Custom Injector

###

Instantiating a connection to a database can involve a significant number of small and moderately complex steps.

Using the Molajo IoCC, that code can be defined once in a custom injector handler and then invoked, each and every time the database connection is needed, using this simple command:

```php
$database = $this->IoC->getService('Database');
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
call - class, options constructor, options setter, either the valid_properties array or reflection, what to do with the instance, callbacks for each method in the options (Filtering?)
3. Services Library (custom configurations)
Simple
Object Graph - lazy loading - optional loading - think through your class object structure and purposefully design the instanatiation https://qtile.readthedocs.org/en/latest/manual/commands/index.html

Any class constructor which asks for an instance of a class that has been marked as shared will be passed the shared instance of the object rather than a new instance.


4. Overrides
5. Third-party


Examples:

IoCC Accessibility
frontcontroller

Roadmap
Considering adding YAML, JSON, XML-type configuration support that could be used instead of the Injector methods. Currently, feel the PHP approach is easier but it might be nice to have a choice conceivable would open some automation with ORM environments.

FAQ

**IoCC, DIC, and Service Locators are the mark of the beast. And, the beast is Martin Fowler.**

That is not a question.

**Will you add annotation or type hints for automated dependency injection.**

It is doubtful. Doing so would be contrary to everything I believe about the danger of relying on comments in code.

** How is a Service Locator different than a IoCC/DIC?
