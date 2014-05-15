<?php

namespace JLM\SerializerExpressionBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
/**
 * JLMSerializerExpressionExtension
 * 
 */
class JLMSerializerExpressionExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'jlm_serializer_expression';
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {      
        $loader = new XmlFileLoader($container, new FileLocator(array(
                        __DIR__.'/../Resources/config')));
        $loader->load('services.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $this->determineFOSRestIntegration($container);
    }

    private function determineFOSRestIntegration(ContainerBuilder $container)
    {
        if (class_exists('FOS\RestBundle\FOSRestBundle')) {
            $container->setDefinition('jlm_serializer_expression.fos_rest_view_handler',
                new Definition('JLM\SerializerExpressionBundle\View\ViewHandler',
                array(new Reference('jlm_serializer_expression.expression_based_exclusion_strategy'))));
        }
    }
}