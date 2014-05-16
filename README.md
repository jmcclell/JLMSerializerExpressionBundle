

# Status Badges (for master)

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/jmcclell/jlmawsbundle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
[![Build Status](https://travis-ci.org/jmcclell/JLMAwsBundle.png?branch=master)](https://travis-ci.org/jmcclell/JLMAwsBundle)
[![Coverage Status](https://coveralls.io/repos/jmcclell/JLMAwsBundle/badge.png?branch=master)](https://coveralls.io/r/jmcclell/JLMAwsBundle?branch=master)
[![Stories in Ready](https://badge.waffle.io/jmcclell/JLMAwsBundle.png?label=ready)](https://waffle.io/jmcclell/JLMAwsBundle)
[![Total Downloads](https://poser.pugx.org/jlm/aws-bundle/downloads.png)](https://packagist.org/packages/jlm/aws-bundle)
[![Latest Stable Version](https://poser.pugx.org/jlm/aws-bundle/v/stable.png)](https://packagist.org/packages/jlm/aws-bundle)

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

The bundle will provide the following pre-configured services.

### jlm_serializer_expression.expression_based_exclusion_strategy
This is the bundled, pre-configured exclusion strategy that will run expressions provided by `@excludeIf` annotations to determine at runtime whether or not to include a particular object property. **This is likely the only service you will need**.

### jlm_serializer_expression.metadata.metadata_factory
This service is an instance of  `Metadata\MetadataFactory` from Johannes Schmitt's [Metadata](http://github.com/schmittjoh/Metadata) library. This factory comes preconfigured with an Annotation driver to read the `excludeIf` annotations and a file based cache using your Symfony application's kernel cache directory. If you choose to instantiate a separate instance of `JLM\SerializerExpression\Exclusion\ExpressionBasedExclusionStrategy` this is the metadata factory you should pass along to it unless you have a good reason for using another.

### jlm_serializer_expression.expression_language
This is the bundled expression language (instance of `Symfony\Component\ExpressionLanguage\ExpressionLanguage` which provides access to all services, containers, and includes a `secure` method for directly passing security expressions. This is the language used by the `@excludeIf` annotation and is the language that the pre-configured exclusion strategy is passed. **For more information on the expression language, see the** [relevant Symfony documentation](http://symfony.com/doc/current/components/expression_language/index.html).

# Usage

# FOSRestBundle Integration


