<?php

namespace CodeBuds\WebPConversionBundle;

use CodeBuds\WebPConversionBundle\DependencyInjection\WebPConversionExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WebPConversionBundle extends Bundle
{
	public function getContainerExtension(): ?ExtensionInterface
	{
		if (null === $this->extension) {
			$this->extension = new WebPConversionExtension();
		}

		return $this->extension;
	}
}
