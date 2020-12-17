<?php

namespace CodeBuds\WebPConversionBundle\Twig;


use CodeBuds\WebPConverter\WebPConverter;
use Exception;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class WebPConversionExtension extends AbstractExtension
{

    private int $quality;

    private string $projectDir;

    public function __construct(int $quality, string $projectDir)
    {
        $this->quality = $quality;
        $this->projectDir = $projectDir;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('cb_webp', [$this, 'setWebpExtension']),
        ];
    }

    /**
     * @param $html
     * @return string
     * @throws Exception
     */
    public function setWebpExtension($html)
    {
        $fullFilePath = "{$this->projectDir}/public{$html}";
        $webPPath = WebPConverter::convertedWebPImagePath($fullFilePath);
        $options = [
            'saveFile' => true,
            'quality' => $this->quality
        ];

        if (!file_exists($webPPath)) {
            WebPConverter::createWebPImage($fullFilePath, $options);
        }
        return explode("{$this->projectDir}/public", $webPPath, 2)[1];
    }
}