<?php

namespace CodeBuds\WebPConversionBundle;

use CodeBuds\WebPConversionBundle\DependencyInjection\WebPConversionExtension;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class WebPConversionBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
            ->scalarNode("quality")->defaultValue(80)->end()
            ->scalarNode("upload_path")->defaultValue('/public/images')->end()
            ->end();
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');
        $quality = $config['quality'];
        $uploadPath = $config['upload_path'];

        $builder->setParameter("webp_conversion.quality", $quality);

        $builder->setParameter("webp_conversion.upload_path", $uploadPath);
    }
}
