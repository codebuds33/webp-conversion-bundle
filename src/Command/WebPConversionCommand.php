<?php

namespace CodeBuds\WebPConversionBundle\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Stopwatch\Stopwatch;
use CodeBuds\WebPConverter\WebPConverter;

class WebPConversionCommand extends Command
{
    protected static $defaultName = 'codebuds:webp:convert';

    private Finder $finder;

    private int $quality;

    private string $projectDir;

    public function __construct(int $quality, string $projectDir)
    {
        $this->finder = new Finder();
        $this->quality = $quality;
        $this->projectDir = $projectDir;

        parent::__construct(null);
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate webps in directories')
            ->addArgument('directories', InputArgument::IS_ARRAY, 'Directories for webP generation')
            ->addOption('create', null, InputOption::VALUE_NONE, 'Generate new files')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Recreate all the files')
            ->addOption('quality', null, InputOption::VALUE_OPTIONAL, 'Set webp generation quality', $this->quality)
            ->addOption('suffix', null, InputOption::VALUE_OPTIONAL, 'Add a filename suffix', '');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $directories = $input->getArgument('directories');

        if (!$directories) {
            $io->error(sprintf('no directories passed'));

            return 1;
        }

        $stopwatch = new Stopwatch();

        $stopwatch->start('WebP_transforms');

        $images = [];

        array_walk($directories, function ($directory) use ($io, &$images) {
            $fullPath =  $directory[0] === '/' ?: "{$this->projectDir}/{$directory}";
            $images = $this->scanDirs($fullPath);
        });

        if ($input->getOption('create')) {
            $progressBar = new ProgressBar($output, count($images));
            $progressBar->start();

            $errors = [];
            array_walk($images, function ($image) use ($io, $progressBar, &$errors, $input) {
                $progressBar->advance();
                try {
                    $options = [
                        'quality' => (int)$input->getOption('quality'),
                        'force' => $input->getOption('force'),
                        'saveFile' => $input->getOption('create'),
                        'filenameSuffix' => $input->getOption('suffix'),
                    ];
                    WebPConverter::createWebPImage($image["path"], $options);
                } catch (Exception $exception) {
                    $errors[] = $exception->getMessage();
                }
            });
            $progressBar->finish();

            if ($errors) {
                $io->error($errors);
            }
        } else {
            $io->text(count($images) . " images found, add --create to start webP generation");
        }

        $event = $stopwatch->stop('WebP_transforms');
        $io->text("Time : " . (string) $event);

        return 0;
    }

    /**
     * @param string $fullPath
     * @return array
     */
    private function scanDirs(string $fullPath): array
    {
        $elements = [];
        $this->finder->files()->in($fullPath);
        if ($this->finder->hasResults()) {
            foreach ($this->finder as $file) {
                $elements[] = [
                    "name" => $file->getFilenameWithoutExtension(),
                    "extension" => $file->getExtension(),
                    "path" => $file
                ];
            }
        }
        return $elements;
    }
}
