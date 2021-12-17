<?php


namespace CodeBuds\WebPConversionBundle\Service;


use Exception;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploadHelper
{
	private string $destination;

	public function __construct(string $uploadPath, string $projectDir)
	{
		$this->destination = $projectDir . $uploadPath;
	}

	/**
	 * @throws Exception
	 */
	public function uploadAction(UploadedFile $uploadedFile): File
	{
		$originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
		$newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid('', true) . '.' . $uploadedFile->guessExtension();

		return $uploadedFile->move(
			$this->destination,
			$newFilename
		);
	}
}
