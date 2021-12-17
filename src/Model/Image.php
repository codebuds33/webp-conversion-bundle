<?php

namespace CodeBuds\WebPConversionBundle\Model;


use CodeBuds\WebPConversionBundle\Traits\ConvertibleImageTrait;
use Exception;
use Symfony\Component\HttpFoundation\File\File;

class Image
{
	use ConvertibleImageTrait;

	/**
	 * Image constructor.
	 * @param File|null $file
	 * @throws Exception
	 */
	public function __construct(?File $file = null)
	{
		if ($file) {
			$this->setOriginalInformationFromFile($file);
		} else {
			$this->originalFilename = null;
			$this->originalPath = null;
			$this->originalFileType = null;
			$this->originalExtension = null;
		}
	}
}
