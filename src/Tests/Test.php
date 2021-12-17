<?php

use CodeBuds\WebPConversionBundle\Command\WebPConversionCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class Test extends KernelTestCase
{
	protected Application $application;

	public function setUp(): void
	{
		$kernel = self::bootKernel();
		$this->application = new Application($kernel);
		$this->application->add(new WebPConversionCommand(80, __DIR__));
		$command = $this->application->find('codebuds:webp:convert');
		$this->commandTester = new CommandTester($command);
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
		unlink($path);
	}
}
