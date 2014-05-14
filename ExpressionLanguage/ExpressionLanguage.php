<?php

namespace JLM\SerializerExpressionBundle\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage;
use Symfony\Component\ExpressionLanguage\Expression;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ParserCache\ParserCacheInterface;

/**
 */
class ExpressionLanguage extends BaseExpressionLanguage
{
    protected $container;

    public function __construct(ContainerInterface $container, ParserCacheInterface $cache = null)
    {  
        $this->container = $container;
        parent::__construct($cache);        
    }

    protected function registerFunctions()
    {
        parent::registerFunctions();

        $container = $this->container;

        $this->register('service', function ($arg) {
            return sprintf('$this->get(%s)', $arg);
        }, function (array $variables, $value)  use ($container) {
            return $container->get($value);
        });

        $this->register('param', function ($arg) {
            return sprintf('$this->getParameter(%s)', $arg);
        }, function (array $variables, $value) use ($container) {
            return $container->getParameter($value);
        });

        $this->register('secure', function ($arg) {
            return sprintf('$this->get(\'security.context\')->isGranted(%s)', $arg);
        }, function (array $variables, $value) use ($container) {   
            return $container->get('security.context')->isGranted(new Expression($value));
        });
    }
}
