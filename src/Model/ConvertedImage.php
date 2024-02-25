<?php

namespace CodeBuds\WebPConversionBundle\Model;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\File;

class ConvertedImage
{
    private string $originalPath;

    private string $originalFilename;

    private string $originalExtension;

    private string $originalFileType;

    private ?int $conversionQuality;

    private ?string $convertedPath;

    private ?string $convertedFilename;

    private ?string $convertedFilenameSuffix;

    /**
     * @return string
     */
    public function getOriginalPath(): string
    {
        return $this->originalPath;
    }

    /**
     * @param string $originalPath
     */
    public function setOriginalPath(string $originalPath): void
    {
        $this->originalPath = $originalPath;
    }

    /**
     * @return string
     */
    public function getOriginalFilename(): string
    {
        return $this->originalFilename;
    }

    /**
     * @param string $originalFilename
     */
    public function setOriginalFilename(string $originalFilename): void
    {
        $this->originalFilename = $originalFilename;
    }

    /**
     * @return string
     */
    public function getOriginalExtension(): string
    {
        return $this->originalExtension;
    }

    /**
     * @param string $originalExtension
     */
    public function setOriginalExtension(string $originalExtension): void
    {
        $this->originalExtension = $originalExtension;
    }

    /**
     * @return string
     */
    public function getOriginalFileType(): string
    {
        return $this->originalFileType;
    }

    /**
     * @param string $originalFileType
     */
    public function setOriginalFileType(string $originalFileType): void
    {
        $this->originalFileType = $originalFileType;
    }

    /**
     * @return int
     */
    public function getConversionQuality(): int
    {
        return $this->conversionQuality;
    }

    /**
     * @param int $conversionQuality
     */
    public function setConversionQuality(int $conversionQuality): void
    {
        $this->conversionQuality = $conversionQuality;
    }

    /**
     * @return string
     */
    public function getConvertedPath(): string
    {
        return $this->convertedPath;
    }

    /**
     * @param string $convertedPath
     */
    public function setConvertedPath(string $convertedPath): void
    {
        $this->convertedPath = $convertedPath;
    }

    /**
     * @return string
     */
    public function getConvertedFilename(): string
    {
        return $this->convertedFilename;
    }

    /**
     * @param string $convertedFilename
     */
    public function setConvertedFilename(string $convertedFilename): void
    {
        $this->convertedFilename = $convertedFilename;
    }

    /**
     * @return string
     */
    public function getConvertedFilenameSuffix(): string
    {
        return $this->convertedFilenameSuffix;
    }

    /**
     * @param string $convertedFilenameSuffix
     */
    public function setConvertedFilenameSuffix(string $convertedFilenameSuffix): void
    {
        $this->convertedFilenameSuffix = $convertedFilenameSuffix;
    }

    /**
     * @param File $file
     * @return $this
     */
    public function setOriginalInformationFromFile(File $file): self
    {
        $this->originalPath = $file->getPath();
        $this->originalFilename = substr($file->getFilename(), 0, strrpos($file->getFilename(), '.'));
        $this->originalExtension = $file->getExtension();

        $fileType = $file->guessExtension();

        if($fileType === null) {
            throw new Exception(" extension cannot be guessed");
        }

        $this->originalFileType = $fileType;

        return $this;
    }

}