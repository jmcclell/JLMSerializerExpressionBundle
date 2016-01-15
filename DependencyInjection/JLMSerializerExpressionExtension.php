<?php

namespace JLM\SerializerExpressionBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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

        $this->determineFOSRestIntegration($container, $loader);
    }

    private function determineFOSRestIntegration(ContainerBuilder $container, $loader)
    {
        if (class_exists('FOS\RestBundle\FOSRestBundle')) {
            $loader->load('fos_rest_services.xml');
        }
    }
}