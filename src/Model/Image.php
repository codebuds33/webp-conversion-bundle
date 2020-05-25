<?php

namespace CodeBuds\WebPConversionBundle\Model;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\File;

class Image
{
    private File $file;

    private string $originalPath;

    private string $originalFilename;

    private string $originalExtension;

    private string $originalFileType;

    private ConversionSettings $settings;

    /**
     * ConvertedImage constructor.
     * @param File|string $file
     * @throws \Exception
     */
    public function __construct($file)
    {
        $file instanceof File ?: $file = new File($file);
        $this->file = $file;
        $this->settings = new ConversionSettings();
        $this->setOriginalInformationFromFile($file);
    }

    /**
     * @return string|File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getOriginalPath(): string
    {
        return $this->originalPath;
    }

    /**
     * @return string
     */
    public function getOriginalFilename(): string
    {
        return $this->originalFilename;
    }

    /**
     * @return string
     */
    public function getOriginalExtension(): string
    {
        return $this->originalExtension;
    }

    /**
     * @return string
     */
    public function getOriginalFileType(): string
    {
        return $this->originalFileType;
    }

    /**
     * @return ConversionSettings
     */
    public function getSettings(): ConversionSettings
    {
        return $this->settings;
    }

    /**
     * @param ConversionSettings $settings
     */
    public function setSettings(ConversionSettings $settings): void
    {
        $this->settings = $settings;
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

        if ($fileType === null) {
            throw new Exception("Extension cannot be guessed");
        }

        $this->originalFileType = $fileType;

        return $this;
    }
}