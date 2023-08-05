<?php

namespace CodeBuds\WebPConversionBundle\Model;

class WebPInformation
{
    private readonly int $quality;

    private readonly string $filename;

    private readonly string $path;

    private readonly string $filenameSuffix;

    private readonly bool $saved;

    public function __construct(array $information)
    {
        $this->path = $information["options"]["savePath"];
        $this->quality = $information["options"]["quality"];
        $this->filename = $information["options"]["filename"];
        $this->filenameSuffix = $information["options"]["filenameSuffix"];
        $this->saved = $information["options"]["saveFile"];
    }

    public function getQuality(): int
    {
        return $this->quality;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFilenameSuffix(): string
    {
        return $this->filenameSuffix;
    }

    public function isSaved(): bool
    {
        return $this->saved;
    }

    public function getConvertedFullPath(): string
    {
        return "{$this->path}/{$this->filename}{$this->filenameSuffix}.webp";
    }
}
