<?php

namespace CodeBuds\WebPConversionBundle\Service;

use CodeBuds\WebPConversionBundle\Model\Image;
use CodeBuds\WebPConversionBundle\Model\WebPInformation;
use CodeBuds\WebPConverter\WebPConverter;
use Exception;

class ImageConverter
{
    /**
     * @param $image
     * @param bool $saveFile
     * @param bool $force
     * @return array|mixed
     * @throws Exception
     */
    public function convert($image, bool $force = false, bool $saveFile = true)
    {
        $options = $this->createOptionsArray($image);
        $options['saveFile'] = $saveFile;
        $options['force'] = $force;

        return new WebPInformation(WebPConverter::createWebPImage($image->getImageFile(), $options));
    }

    /**
     * @param Image $image
     * @return bool
     * @throws Exception
     */
    public function convertedImageExists(Image $image): bool
    {
        return (file_exists($image->getConvertedFullPath()));
    }

    /**
     * @param $image
     * @return array
     */
    private function createOptionsArray($image): array
    {
        return [
            'quality' => $image->getQuality(),
            'savePath' => $image->getConvertedPath(),
            'filename' => $image->getConvertedFilename(),
            'filenameSuffix' => $image->getConvertedFilenameSuffix()
        ];
    }
}
