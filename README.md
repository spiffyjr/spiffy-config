# SpiffyConfig Module

SpiffyConfig is a module designed to speed up configuration.

## Installation

Installation of SpiffyConfig uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

```sh
php composer.phar require spiffy/spiffy-config:dev-master
```

Then add `SpiffyConfig` to your `config/application.config.php`

Installation without composer is not officially supported, and requires you to install and autoload
the dependencies specified in the `composer.json`.

Finally, copy `config/spiffyconfig.global.php.dist` to `autoload/spiffyconfig.global.php` directory. This will setup
the Application module out of the box.

## Resolvers

Resolvers pass information to builders so that the builders know what to work on.

 * File: resolves files using Symfony's file finder.

## Builders

Builders take information from the resolvers and build configurations based on that information.
 * RouteBuilder: reads route annotations and builds configuration via events.
 * ServiceBuilder: reads service annotations and builds configuration via events.
 * TemplateBuilder: takes files from the file resolver and builds a template_map from them.

## Supported Annotations

Below is a list of currently supported annotations. This list will be updated as more annotations are supported. In order
to use the annotations you **must import them first**. Do this by putting the following at the top of your code,

```php
<?php

use SpiffyConfig\Annotation as Config;
```

This will let you use SpiffyConfig's annotations using `@Config` in your docblock.

### Service

Service annotations are found in the `SpiffyConfig\Annotation\Service` namespace and handle setting up invokables and
factories on various service managers.

Properties:
  *    key: the key to use when defining the configuration (default: service_manager). You can use a pipe "|" to nest
            the key in the configuration array, e.g., my|nested would set the services as:
             ```php
             array(
                'my' => array(
                    'nested' => array(
                        // service configuration would go here
                    )
                )
             )
             ```
  * shared: set the service as shared or not (default: null)
  *   type: this is set by using the `SpiffyConfig\Annotation\Service\Factory` or
            `SpiffyConfig\Annotation\Service\Invokable` annotation (default: factories/invokables repsectively)
  *   name: the name to use for the service. If no name is specified the FQCN is used.

There are several annotations that extend the service annotations and predefine the `key` property to save you the
extra step. Each of these have a Factory and Invokable annotation available.

Service Annotations:
  * `SpiffyConfig\Annotation\Controller`: key set to `controllers`
  * `SpiffyConfig\Annotation\Form`: key set to `form_elements`
  * `SpiffyConfig\Annotation\Hydrator`: key set to `hydrators`
  * `SpiffyConfig\Annotation\RouteManager`: key set to `route_manager`

```php
namespace Application\Service;

use SpiffyConfig\Annotation\Service;

/**
 * This annotation will set the Mailer service using the service_manager key
 * under the service name "Application\Service\Mailer". I could specify a name
 * if I don't want to use the FQCN.
 *
 * @Service\Invokable
 */
class Mailer implements ServiceLocatorAwareInterface
{
    // ... implementation ...
}
```

```php
namespace Application\Service;

use SpiffyConfig\Annotation\Service;

/**
 * This annotation will set the Mailer service using the service_manager key
 * under the service name "mailer" and being built from the "Application\Service\MailerFactory"
 * factory.
 *
 * @Service\Factory("Application\Service\MailerFactory", name="mailer")
 */
class Mailer implements ServiceLocatorAwareInterface
{
    // ... implementation ...
}
```

### Route

Route annotations handle setting up routes directly in your controllers. They can be set at the class level or the
method level. If set on the class level you must specify the action that the route pertains to. If set directly on the
method then the action is set for you.

Currently, the following routes are available:

* Generic: used to setup any type of route you want. Nothing is managed directly.
* Literal: setup literal routes
* Regex: setup regex routes.
* Segment: setup segment routes.

Example:

```php
<?php

namespace Application\Controller;

use SpiffyConfig\Annotation\Route;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * @Route\Literal("/", name="home", action="index")
 */
class IndexController extends AbstractActionController
{
    // This annotation is set on the controller level.
    public function indexAction()
    {
        // ...
    }

    /**
     * @Route\Segment("/foo/:id", name="foo", options={"constraints":{"id":"\d+"}}")
     */
    public function fooAction()
    {
        // ...
    }

    /**
     * @Route\Literal("/bar", name="bar", parent="foo")
    public function barAction()
    {
        // ...
    }
}
```

### Controller

Controllers have an additional `RouteParent` annotation other than the service annotations listed above. This
annotation let's you set the parent for all actions in the current controller.

```php
namespace Application\Controller;

use SpiffyConfig\Annotation\Controller;
use SpiffyConfig\Annotation\Route;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * @Controller\RouteParent("home", action="index")
 */
class IndexController extends AbstractActionController
{
    /**
     * @Route\Literal("/")
     */
    public function indexAction()
    {
        // ...
    }

    /**
     * Resolves to /foo and is named home/foo.

     * @Route\Literal("foo", name="foo")
     */
    public function fooAction()
    {
        // ...
    }
}
```

## Options

All options are available in the [`SpiffyConfig\ModuleOptions`](https://github.com/spiffyjr/spiffy-config/blob/master/src/SpiffyConfig/ModuleOptions.php)
class with detailed descriptions.

## CLI Tool

A CLI tool is provided to build and clear the cache. Run your `public/index.php` from a console to see the relevent
information.

## Automatic Route Names

It's recommended that you specify a name for all routes e.g., `@Route\Literal("/", name="home")`. Failure to do so will
cause an automated route name to be generated based on a canonicalized version of the controller and action name.

For example, if you have a controller registered with the ControllerManager as `My\Controller` and are a adding a route
to the `indexAction` the auto-generated route name would be `my_controller_index`.

## Zend Developer Tools

A toolbar button is provided if you are using ZendDeveloperTools which lists various SpiffyConfig information and allows
you fast access to refreshing the page with the key set.