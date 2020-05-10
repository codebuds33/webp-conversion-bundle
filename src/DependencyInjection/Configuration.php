<?php

namespace CodeBuds\WebPConversionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder("webp_conversion");
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode("quality")->defaultValue(80)->end()
            ->end();

        return $treeBuilder;
    }
}