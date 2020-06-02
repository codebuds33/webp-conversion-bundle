<?php


namespace CodeBuds\WebPConversionBundle\Service;


use CodeBuds\WebPConversionBundle\Model\Image;
use Exception;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

class ImageUploadHelper
{
  private string $destination;

  public function __construct(string $uploadPath, string $projectDir)
  {
    $this->destination = $projectDir . $uploadPath;
  }

  /**
   * @param UploadedFile $uploadedFile
   * @throws Exception
   */
  public function uploadAction(UploadedFile $uploadedFile): File
  {
    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
    $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

    $newFile = $uploadedFile->move(
      $this->destination,
      $newFilename
    );

    return $newFile;
  }
}
