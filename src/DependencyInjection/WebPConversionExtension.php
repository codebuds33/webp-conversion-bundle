<?php

namespace CodeBuds\WebPConversionBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class WebPConversionExtension extends Extension
{
	public function load(array $configs, ContainerBuilder $container): void
	{
		$loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		try {
			$loader->load('services.xml');
		} catch (Exception $exception) {
			var_dump($exception);
		}

		$configuration = $this->getConfiguration($configs, $container);

		$config = $this->processConfiguration($configuration, $configs);

		$definition = $container->getDefinition("codebuds_webp_conversion.command");
		$definition->setArgument(0, $config['quality']);

		$definition = $container->getDefinition("codebuds_webp_conversion.twig_extension");
		$definition->setArgument(0, $config['quality']);

		$definition = $container->getDefinition("codebuds_webp_conversion.webp_conversion");
		$definition->setArgument(0, $config['quality']);

		$definition = $container->getDefinition("codebuds_webp_conversion.service.upload_helper");
		$definition->setArgument(0, $config['upload_path']);
	}
}
