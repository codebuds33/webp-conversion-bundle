<?php


namespace CodeBuds\WebPConversionBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

trait ConvertibleImageTrait
{
    /**
     * @Assert\File(
     *     mimeTypes = {"image/png", "image/jpeg", "image/bmp", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid image (jpeg, png, bmp or gif)",
     * )
     * @var File
     */
    private File $imageFile;

    /**
     * @var int|null
     * @ORM\Column(type="integer",  nullable=true)
     */
    private ?int $quality;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $convertedPath;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $convertedFilename;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $convertedFilenameSuffix;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private ?string $originalPath;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private ?string $originalFilename;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private ?string $originalExtension;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private ?string $originalFileType;

    /**
     * @return File
     */
    public function getImageFile(): File
    {
        return new File($this->getOriginalFullPath());
    }

    /**
     * @param File $imageFile
     * @return ConvertibleImageTrait
     * @throws Exception
     */
    public function setImageFile(File $imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuality(): ?int
    {
        return $this->quality;
    }

    /**
     * @param int|null $quality
     */
    public function setQuality(?int $quality): self
    {
        $this->quality = $quality;

        return $this;
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
    public function setConvertedPath(?string $convertedPath): self
    {
        $this->convertedPath = $convertedPath;

        return $this;
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
    public function setConvertedFilename(?string $convertedFilename): self
    {
        $this->convertedFilename = $convertedFilename;

        return $this;
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
    public function setConvertedFilenameSuffix(?string $convertedFilenameSuffix): self
    {
        $this->convertedFilenameSuffix = $convertedFilenameSuffix;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOriginalPath(): ?string
    {
        return $this->originalPath;
    }

    /**
     * @param string|null $originalPath
     */
    public function setOriginalPath(?string $originalPath): self
    {
        $this->originalPath = $originalPath;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    /**
     * @param string|null $originalFilename
     * @return ConvertibleImageTrait
     */
    public function setOriginalFilename(?string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOriginalExtension(): ?string
    {
        return $this->originalExtension;
    }

    /**
     * @param string|null $originalExtension
     * @return ConvertibleImageTrait
     */
    public function setOriginalExtension(?string $originalExtension): self
    {
        $this->originalExtension = $originalExtension;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOriginalFileType(): ?string
    {
        return $this->originalFileType;
    }

    /**
     * @param string|null $originalFileType
     * @return ConvertibleImageTrait
     */
    public function setOriginalFileType(?string $originalFileType): self
    {
        $this->originalFileType = $originalFileType;

        return $this;
    }

    /**
     * @param File $file
     * @return $this
     * @throws Exception
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

    public function getOriginalFullFilename(): string
    {
        return "{$this->originalFilename}.{$this->originalExtension}";
    }

    public function getOriginalFullPath(): string
    {
        return "{$this->originalPath}/{$this->originalFilename}.{$this->originalExtension}";
    }

    public function getConvertedFullPath(): string
    {
        return "{$this->convertedPath}/{$this->convertedFilename}{$this->getConvertedFilenameSuffix()}.webp";
    }
}
