# Status Badges (for master)

[![Build Status](https://travis-ci.org/jmcclell/JLMAwsBundle.png?branch=master)](https://travis-ci.org/jmcclell/JLMAwsBundle)
[![Coverage Status](https://coveralls.io/repos/jmcclell/JLMSerializerExpressionBundle/badge.png?branch=master)](https://coveralls.io/r/jmcclell/JLMSerializerExpressionBundle?branch=master)
[![Total Downloads](https://poser.pugx.org/jlm/serializer-expression-bundle/downloads.png)](https://packagist.org/packages/jlm/serializer-expression-bundle)
[![Latest Stable Version](https://poser.pugx.org/jlm/serializer-expression-bundle/v/stable.png)](https://packagist.org/packages/jlm/serializer-expression-bundle)

#JLMSerializerExpressionBundle

This bundle adds makes it simple to integrate the [JLMSerializerExpression](http://github.com/jmcclell/JLMSerializerExpression) library into your Symfony application. The library adds an `@excludeIf` annotation with expression language support to [JMSSerializerBundle](https://github.com/schmittjoh/JMSSerializerBundle) so that individual fields can be hidden based on expressions at runtime.

This bundle registers the necessary services in the DI container including a pre-configured `ExclusionStrategy` object which can be added directly to any `SerializationContext`.

# Installation

This bundle can be installed via Composer by adding the following to your ```composer.json``` file:

```json

"require": {
    # ..
    "jlm/serializer-expression-bundle": "dev-master"
    # ..
}
```

Then add the bundle to your Kernel in ```AppKernel.php``` (if using Symfony Standard):

```php
public function registerBundles()
    {
        $bundles = array(
            /* ... */
            new JLM\SerializerExpressionBundle\JLMSerializerExpressionBundle(),
        );

        return $bundles;
    }
```

# Configuration

This bundle currently has no configuration exposed. It will work out of the box.

# Services

The bundle will provide the following pre-configured services. Please see the [SerializerExpression library](http://github.com/jmcclell/JLMSerializerExpression) for more detail on what these individual services are for.

### jlm_serializer_expression.expression_based_exclusion_strategy
This is the bundled, pre-configured exclusion strategy that will run expressions provided by `@excludeIf` annotations to determine at runtime whether or not to include a particular object property. **This is likely the only service you will need**.

### jlm_serializer_expression.metadata.metadata_factory
This service is an instance of  `Metadata\MetadataFactory` from Johannes Schmitt's [Metadata](http://github.com/schmittjoh/Metadata) library. This factory comes preconfigured with an Annotation driver to read the `excludeIf` annotations and a file based cache using your Symfony application's kernel cache directory. If you choose to instantiate a separate instance of `JLM\SerializerExpression\Exclusion\ExpressionBasedExclusionStrategy` this is the metadata factory you should pass along to it unless you have a good reason for using another.

### jlm_serializer_expression.expression_language
This is the bundled expression language (instance of `Symfony\Component\ExpressionLanguage\ExpressionLanguage` which provides access to all services, containers, and includes a `secure` method for directly passing security expressions. This is the language used by the `@excludeIf` annotation and is the language that the pre-configured exclusion strategy is passed. **For more information on the expression language, see the** [relevant Symfony documentation](http://symfony.com/doc/current/components/expression_language/index.html).

The bundled version of the `ExpressionLanguage` provides the following methods:

- **param** - Accepts the name of a parameter and returns its value, eg: `@excludeIf("param('myParam') == true")`
- **service** - Accepts the ID of a service and returns it, eg: `@excludeIf("service('mySerivce').foo()")`
- **secure** - Accepts a string which is itself an expression, passed directly to the security context, eg: `@excludeIf("secure(""has_role('ROLE_ADMIN')"")") (Note the double quotes which is how we embed an expression within an expression)

### jlm_serializer_expression.fos_rest_view_handler

This service provides integration with `FOSRestBundle`, detailed below.

### Annotating Your Classes

```php
<?php

use JLM\SerializerExpression\Annotation\ExcludeIf;

class User
{
    
    public $firstName = 'Jason';

    public $lastName = 'McClellan';
   /**
     * @ExcludeIf("secure(""has_role('ROLE_ADMIN')"")")
     */
    public $phone = '555-555-5555';
    /**
     * @ExcludeIf("secure(""has_role('ROLE_ADMIN')"")")
     */
    public $address ='New York, NY';

    public $occupation = 'Software';
}
```

The above annotations would ensure that the `$phone` and `$address` fields are only serialized for users with the role `ROLE_ADMIN`. Notice we are using the `secure` function which is provided by this bundle's `ExpressionLanguage` implementation.

### Using the Exclusion Strategy

When serializing the object using an instance of `JMS\Serializer\Serializer`, you must add our exclusion strategy to the `SerializationContext` instance and pass it to the `serialize()` method along with the object instance and desired format. Here is a rather verbose example:

```php
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = ...
        $serializationContext = SerializationContext::create();
        $exclusionStrategy = $this->get('jlm_serializer_expression.expression_based_exclusion_strategy');
        $serializationContext->addExclusionStrategy($exclusionStrategy);
        // $serializer = $this->get('jms_serializer') // if using JMSSerializerBundle
        $serializer = SerializerBuilder::create()->build();

        $serializedContent = $serializer->serialize($data, 'json', $serializationContext);
        
        $response = new Response($serializedContent);
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
}

```

# FOSRestBundle Integration

If you are using [FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle) in order to more easily create format-agnostic controllers (and you should!) then the serialization process is *far* easier. The only thing you have to do is change a bit of configuration for `FOSRestBundle` so that it uses the `ViewHandler` provided by this bundle, which will automatically add the new `ExpressionBasedExclusionStrategy` to the built-in `SerializationContext` used by `FOSRestBundle`.

```yaml
fos_rest:
  service:
    view_handler: jlm_serializer_expression.fos_rest_view_handler
```

That's it! Your annotated classes will automatically be serialized properly, respecting the `@excludeIf` annotations.
