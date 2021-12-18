<?php

namespace CodeBuds\WebPConversionBundle;

use CodeBuds\WebPConversionBundle\Model\Image;
use CodeBuds\WebPConversionBundle\Model\WebPInformation;
use CodeBuds\WebPConversionBundle\Service\ImageConverter;
use Exception;
use Symfony\Component\HttpFoundation\File\File;

class WebPConversion
{

    public function __construct(private int $quality, private ImageConverter $imageConverter)
    {
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
