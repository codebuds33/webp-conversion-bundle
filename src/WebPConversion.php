<?php

namespace CodeBuds\WebPConversionBundle;

use CodeBuds\WebPConversionBundle\Model\Image;
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
     * @param File $imageFile
     * @param bool $force
     * @return array|mixed
     * @throws Exception
     */
    public function convert(File $imageFile, bool $force = false)
    {
        $image = new Image($imageFile);

        if ($image->getQuality() === null) {
            $image->setQuality($this->quality);
        }

        if ($this->imageConverter->convertedImageExists($image) && $force) {
            return $this->imageConverter->convert($image, true);
        }
        return $this->imageConverter->convert($image);
    }
}
