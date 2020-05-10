<?php

namespace CodeBuds\WebPConversionBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class WebPConversionExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        try {
            $loader->load('services.xml');
        } catch (\Exception $exception) {
            var_dump($exception);
        }

        $configuration = $this->getConfiguration($configs, $container);

        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition("codebuds_webp_conversion_command.webp_conversion_command");
        $definition->setArgument(0, $config['quality']);

        $definition = $container->getDefinition("codebuds_webp_conversion_twig_extension.webp_conversion_twig_extension");
        $definition->setArgument(0, $config['quality']);
    }
}