<?php

namespace CodeBuds\WebPConversionBundle\Tests;

use CodeBuds\WebPConversionBundle\Command\WebPConversionCommand;
use CodeBuds\WebPConversionBundle\Model\Image;
use CodeBuds\WebPConversionBundle\Service\ImageConverter;
use CodeBuds\WebPConversionBundle\Twig\WebPConversionExtension;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class Test extends KernelTestCase
{
    protected Application $application;

    private ImageConverter $imageConverterService;

    private WebPConversionExtension $webPConversionExtension;

    public function setUp(): void
    {
        $quality = 80;
        $projectDir = __DIR__;
        $kernel = self::bootKernel(['test' => true]);
        $container = self::$kernel->getContainer();
        $this->application = new Application($kernel);
        $this->application->add(new WebPConversionCommand($quality, $projectDir));
        $command = $this->application->find('codebuds:webp:convert');
        $this->commandTester = new CommandTester($command);
        /** @var ImageConverter imageConverterService */
        $this->imageConverterService = $container->get(ImageConverter::class);
        $this->webPConversionExtension = new WebPConversionExtension($quality, $projectDir);
    }

    public function testCommand(): void
    {
        $this->commandTester->execute([]);
        // the output of the command in the console
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('[ERROR] no directories passed', $output);

        $this->commandTester->execute([
            'directories' => ['Data']
        ]);
        // the output of the command in the console
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('4 images found, add --create to start webP generation', $output);

        $this->commandTester->execute([
            'directories' => ['Data'],
            '--create' => null
        ]);
        // the output of the command in the console
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('4/4 [============================] 100%', $output);

        $this->deleteGeneratedFiles();

        $this->commandTester->execute([
            'directories' => ['Data'],
            '--create' => null,
            '--suffix' => '_suffix'
        ]);

        $this->assertFileExists(__DIR__ . '/Data/png_suffix.webp');
        $this->deleteGeneratedFiles('suffix');
    }

    private function deleteGeneratedFiles(?string $fileSuffix = null): void
    {
		$files = ['png', 'jpg', 'jpeg', 'gif'];
		foreach ($files as $file) {
			$filename = $fileSuffix ? $file . '_' . $fileSuffix : $file;
			$filename .= '.webp';
			$path = __DIR__ . '/Data/' . $filename;
			if (file_exists($path)) {
				unlink($path);
			}
		}
    }

    public function testService(): void
    {
        $file = new UploadedFile(__DIR__ . '/Data/png.png', 'image/png', UPLOAD_ERR_OK, true);
        $convertedFileName = 'png_convert';
        $path = __DIR__ . '/Data/';
        $image = (new Image($file))
            ->setConvertedFilename($convertedFileName)
            ->setConvertedPath($path);
        $this->imageConverterService->convert($image, force: true);
        $this->assertFileExists($path . $convertedFileName . '.webp');

        $this->assertTrue($this->imageConverterService->convertedImageExists($image));

        $this->deleteGeneratedFiles('convert');
    }

    public function testTwigExtension(): void
    {
        $filePath = '/png.png';
        $this->webPConversionExtension->setWebpExtension($filePath, publicDirectory: 'Data');
        $this->assertFileExists(__DIR__ . '/Data/png.png');
        $this->deleteGeneratedFiles();

        $this->expectException(FileNotFoundException::class);
        $filePath = '/non-existing.png';
        $this->webPConversionExtension->setWebpExtension($filePath, returnEmptyOnException: false);

        $response = $this->webPConversionExtension->setWebpExtension($filePath, returnEmptyOnException: true);

        $this->assertSame('', $response);
    }
}
