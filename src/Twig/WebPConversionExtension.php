<?php

namespace CodeBuds\WebPConversionBundle\Twig;

use CodeBuds\WebPConverter\WebPConverter;
use Exception;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class WebPConversionExtension extends AbstractExtension
{
    public function __construct(private readonly int $quality, private readonly string $projectDir)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('cb_webp', $this->setWebpExtension(...)),
        ];
    }

    /**
     * @throws Exception
     */
    public function setWebpExtension(string $filePath, bool $returnEmptyOnException = true, string $publicDirectory = 'public'): string
    {
        $fullFilePath = "{$this->projectDir}/{$publicDirectory}{$filePath}";

        if ($returnEmptyOnException && !file_exists($fullFilePath)) {
            return '';
        }

        $webPPath = WebPConverter::convertedWebPImagePath($fullFilePath);
        $options = [
            'saveFile' => true,
            'quality' => $this->quality
        ];

        if (!file_exists($webPPath)) {
            WebPConverter::createWebPImage($fullFilePath, $options);
        }
        return explode("{$this->projectDir}/{$publicDirectory}", $webPPath, 2)[1];
    }
}
