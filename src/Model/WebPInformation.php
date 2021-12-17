<?php

namespace CodeBuds\WebPConversionBundle\Model;


class WebPInformation
{
	private int $quality;

	private string $filename;

	private string $path;

	private string $filenameSuffix;

	private bool $saved;

	/**
	 * @param array $information
	 */
	public function __construct(array $information)
	{
		$this->path = $information["options"]["savePath"];
		$this->quality = $information["options"]["quality"];
		$this->filename = $information["options"]["filename"];
		$this->filenameSuffix = $information["options"]["filenameSuffix"];
		$this->saved = $information["options"]["saveFile"];
	}

	/**
	 * @return int
	 */
	public function getQuality(): int
	{
		return $this->quality;
	}

	/**
	 * @return string
	 */
	public function getFilename(): string
	{
		return $this->filename;
	}

	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getFilenameSuffix(): string
	{
		return $this->filenameSuffix;
	}

	/**
	 * @return bool
	 */
	public function isSaved(): bool
	{
		return $this->saved;
	}

	/**
	 * @return string
	 */
	public function getConvertedFullPath(): string
	{
		return "{$this->path}/{$this->filename}{$this->filenameSuffix}.webp";
	}

}
