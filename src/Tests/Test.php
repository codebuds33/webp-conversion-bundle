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
        $this->assertStringContainsString('1 images found, add --create to start webP generation', $output);

        $this->commandTester->execute([
            'directories' => ['Data'],
            '--create' => null
        ]);
        // the output of the command in the console
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('1/1 [============================] 100%', $output);

        $this->deleteGeneratedFile();

        $this->commandTester->execute([
            'directories' => ['Data'],
            '--create' => null,
            '--suffix' => '_suffix'
        ]);

        $this->assertFileExists(__DIR__ . '/Data/test_suffix.webp');
        $this->deleteGeneratedFile('test_suffix.webp');
    }

    private function deleteGeneratedFile(string $filename = 'test.webp'): void
    {
        $path = __DIR__ . '/Data/' . $filename;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function testService(): void
    {
        $file = new UploadedFile(__DIR__ . '/Data/test.png', 'image/png', UPLOAD_ERR_OK, true);
        $convertedFileName = 'test_covert';
        $path = __DIR__ . '/Data/';
        $image = (new Image($file))
            ->setConvertedFilename($convertedFileName)
            ->setConvertedPath($path);
        $this->imageConverterService->convert($image, true);
        $this->assertFileExists($path . $convertedFileName . '.webp');

        $this->assertTrue($this->imageConverterService->convertedImageExists($image));

        $this->deleteGeneratedFile('test_covert.webp');
    }

    public function testTwigExtension(): void
    {
        $filePath = '/test.png';
        $this->webPConversionExtension->setWebpExtension($filePath, 'Data');
        $this->assertFileExists(__DIR__ . '/Data/test.png');
        $this->deleteGeneratedFile('test.webp');

        $this->expectException(FileNotFoundException::class);
        $filePath = '/non-existing.png';
        $this->webPConversionExtension->setWebpExtension($filePath, false);

        $filePath = '/non-existing.png';
        $response = $this->webPConversionExtension->setWebpExtension($filePath, true);

        $this->assertSame('', $response);
    }
}
