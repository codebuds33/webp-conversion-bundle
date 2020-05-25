<?php


namespace CodeBuds\WebPConversionBundle\Model;


class ConversionSettings
{
    private ?int $quality;

    private ?string $convertedPath;

    private ?string $convertedFilename;

    private ?string $convertedFilenameSuffix;

    /**
     * @return int
     */
    public function getQuality(): int
    {
        return $this->quality;
    }

    /**
     * @param int $quality
     */
    public function setQuality(int $quality): void
    {
        $this->quality = $quality;
    }

    /**
     * @return string|null
     */
    public function getConvertedPath(): ?string
    {
        return $this->convertedPath;
    }

    /**
     * @param string|null $convertedPath
     */
    public function setConvertedPath(?string $convertedPath): void
    {
        $this->convertedPath = $convertedPath;
    }

    /**
     * @return string|null
     */
    public function getConvertedFilename(): ?string
    {
        return $this->convertedFilename;
    }

    /**
     * @param string|null $convertedFilename
     */
    public function setConvertedFilename(?string $convertedFilename): void
    {
        $this->convertedFilename = $convertedFilename;
    }

    /**
     * @return string|null
     */
    public function getConvertedFilenameSuffix(): ?string
    {
        return $this->convertedFilenameSuffix;
    }

    /**
     * @param string|null $convertedFilenameSuffix
     */
    public function setConvertedFilenameSuffix(?string $convertedFilenameSuffix): void
    {
        $this->convertedFilenameSuffix = $convertedFilenameSuffix;
    }
}