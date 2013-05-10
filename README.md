Molajo Inversion of Control
===========================

The Molajo **Inversion of Control** package offers a full featured dependency injection solution that is simple to implement within any PHP application. Specific features include: automatic convention-based instantiation, custom configuration using normal PHP code; constructor and setter injection; lazy loading; triggered instantiation; new, existing, cloned, and singleton instances, and so on, and so on.

Considering the amount of time developers invest in writing code  that does nothing more than retrieve dependencies, verify correctness, ensure proper sequencing, instantiate objects, store instances, and so on, implementing an IoCC in your application can significantly increase productivity for developers and even empower frontend developers to begin using functions that were far to complex to use before.

*Example of Service Request:*
As developers know, instantiating a connection to the application database can involve a significant number of small and moderately complex steps. One must retrieve configuration data, extract user, password, database data, verify ACL, which means instantiate the User object, and so on. It can be overwhelming, encourages mindless copy and paste coding, difficult to maintain as changes are made, not ideal. It can be a significant amount of uninteresting code that is repeated too many times in an application.

Using the Molajo IoCC, that code can be defined once, stored within a custom injector and invoked using this simple command:

```php
$database = $this->IoC->getService('Database');
```
Behind the scenes, dependencies trigger other *getService* requests, and those objects need more information, but developers are blissfully unaware of this processing since the IoCC manages this process for them.

## Molajo IoC Architecture
The Molajo IoC Package offers a full featured support structure. Many times, other IoC packages offer the basic Container class, but nothing useful to implement it within an application. The Molajo IoC package, on the other hand, provides all of the elements needed to provide the structure an application needs  to work with dependency injection.

### Container
The [Container class](https://github.com/Molajo/IoC/blob/master/Container.php) and [Interface](https://github.com/Molajo/IoC/blob/master/Api/ContainerInterface.php) are the kernel of the IoC process.

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

## Type of Instance
The `getService` request can request one of four types of instances:
1. **Request a new instance** This is done transparently by requesting a service where a like named service is not stored in the Container instance array.
2. **Accept an available instance, if available, or create a new instance if it is not.** Special consideration of how the class object map loads is needed to use this option with confidence.
3. **Accept an available instance, if available, or proceed without an instance if an existing instance does not exist.** This request is made by passing in the `$options['if_exists']` and setting it to true. It might not be immediately apparent but this approach is necessary, especially during startup, for those situations where both classes are dependent on one another. An example might be a request for the `Database Object` which has a dependency on the `User Object`. Since the `User Object` requires a database read, the same is true in reverse. Again, careful consideration of how classes work together is always needed.
4. **Request a Singleton, or Static, Instance** As much as some might disagree, there are times when a `Singleton` will be desired. Examples might include database connections where performance would be significantly harmed if each interaction required a new connection instance. This type of instance always requires a custom injector where the class `static_service_instance` property is set to true for this request.

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

**IoCC, DIC, and Service Locators are the mark of the beast. The beast is Martin Fowler.**

That is not a question. Please, stop reading academic books and articles about patterns and jump down off that high-horse of religious technology purity. Much easier to learn if you put your time into writing code that runs on servers. Then, after you've been beaten down by real life coding experience, install this or any other IoCC or DIC, and think about the benefits identified. If, at that time, you are able to arrange a set of words into a sentence with a question mark at the end, feel free to ask a question.

**Will you add annotation or type hints for automated dependency injection.**

It is doubtful. Doing so would be contrary to everything I believe about the danger of relying on comments in code.

** How is a Service Locator different than a IoCC/DIC?
