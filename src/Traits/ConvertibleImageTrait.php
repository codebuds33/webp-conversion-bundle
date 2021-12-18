<?php


namespace CodeBuds\WebPConversionBundle\Traits;

use CodeBuds\WebPConversionBundle\Model\Image;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

trait ConvertibleImageTrait
{
    /**
     * @Assert\File(
     *     mimeTypes = {"image/png", "image/jpeg", "image/bmp", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid image (jpeg, png, bmp or gif)",
     * )
     */
    private File $imageFile;

    /**
     * @ORM\Column(type="integer",  nullable=true)
     */
    private ?int $quality = 80;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $convertedPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $convertedFilename;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $convertedFilenameSuffix = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $originalPath;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $originalFilename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $originalExtension;

    /**
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
     * @return ConvertibleImageTrait|Image
     */
    public function setImageFile(File $imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    public function getOriginalFullPath(): string
    {
        return "{$this->originalPath}/{$this->originalFilename}.{$this->originalExtension}";
    }

    public function getQuality(): ?int
    {
        return $this->quality;
    }

    public function setQuality(?int $quality): self
    {
        $this->quality = $quality;

        return $this;
    }

    public function getConvertedPath(): ?string
    {
        return $this->convertedPath;
    }

    public function setConvertedPath(?string $convertedPath): self
    {
        $this->convertedPath = $convertedPath;

        return $this;
    }

    public function getConvertedFilename(): ?string
    {
        return $this->convertedFilename;
    }

    public function setConvertedFilename(?string $convertedFilename): self
    {
        $this->convertedFilename = $convertedFilename;

        return $this;
    }

    public function getOriginalPath(): ?string
    {
        return $this->originalPath;
    }

    public function setOriginalPath(?string $originalPath): self
    {
        $this->originalPath = $originalPath;

        return $this;
    }

    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    public function setOriginalFilename(?string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    public function getOriginalExtension(): ?string
    {
        return $this->originalExtension;
    }

    public function setOriginalExtension(?string $originalExtension): self
    {
        $this->originalExtension = $originalExtension;

        return $this;
    }

    public function getOriginalFileType(): ?string
    {
        return $this->originalFileType;
    }

    public function setOriginalFileType(?string $originalFileType): self
    {
        $this->originalFileType = $originalFileType;

        return $this;
    }

    public function setOriginalInformationFromFile(File $file): self
    {
        $this->originalPath = $file->getPath();
        $this->originalFilename = substr($file->getFilename(), 0, strrpos($file->getFilename(), '.'));
        $this->originalExtension = $file->getExtension();

        $fileType = $file->guessExtension();

        if ($fileType === null) {
            throw new RuntimeException("Extension cannot be guessed");
        }

        $this->originalFileType = $fileType;

        return $this;
    }

    public function getOriginalFullFilename(): string
    {
        return "{$this->originalFilename}.{$this->originalExtension}";
    }

    public function getConvertedFullPath(): string
    {
        return "{$this->convertedPath}/{$this->convertedFilename}{$this->getConvertedFilenameSuffix()}.webp";
    }

    public function getConvertedFilenameSuffix(): ?string
    {
        return $this->convertedFilenameSuffix;
    }

    public function setConvertedFilenameSuffix(?string $convertedFilenameSuffix): self
    {
        $this->convertedFilenameSuffix = $convertedFilenameSuffix;

        return $this;
    }
}
