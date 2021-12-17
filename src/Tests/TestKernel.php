<?php

namespace CodeBuds\WebPConversionBundle\Tests;

use CodeBuds\WebPConversionBundle\WebPConversionBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{

	public function registerBundles(): iterable
	{
		return [
			new WebPConversionBundle()
		];
	}

	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		// TODO: Implement registerContainerConfiguration() method.
	}
}
