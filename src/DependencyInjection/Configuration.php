<?php

namespace CodeBuds\WebPConversionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
	public function getConfigTreeBuilder(): TreeBuilder
	{
		$treeBuilder = new TreeBuilder("webp_conversion");
		$rootNode = $treeBuilder->getRootNode();

		$rootNode
			->children()
			->scalarNode("quality")->defaultValue(80)->end()
			->scalarNode("upload_path")->defaultValue('/public/images')->end()
			->end();

		return $treeBuilder;
	}
}
