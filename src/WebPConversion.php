<?php

namespace CodeBuds\WebPConversionBundle;

use CodeBuds\WebPConversionBundle\Model\Image;
use CodeBuds\WebPConversionBundle\Model\WebPInformation;
use CodeBuds\WebPConversionBundle\Service\ImageConverter;
use Exception;
use Symfony\Component\HttpFoundation\File\File;

class WebPConversion
{
	private ImageConverter $imageConverter;
	private int $quality;

	public function __construct(int $quality, ImageConverter $imageConverter)
	{
		$this->imageConverter = $imageConverter;
		$this->quality = $quality;
	}

	/**
	 * @throws Exception
	 */
	public function convert(File $imageFile, bool $force = false): WebPInformation
	{
		$image = new Image($imageFile);

		if ($image->getQuality() === null) {
			$image->setQuality($this->quality);
		}

		if ($force && $this->imageConverter->convertedImageExists($image)) {
			return $this->imageConverter->convert($image, true);
		}
		return $this->imageConverter->convert($image);
	}
}
