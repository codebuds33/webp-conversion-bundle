<?php

namespace CodeBuds\WebPConversionBundle\Command;

use CodeBuds\WebPConverter\WebPConverter;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsCommand('codebuds:webp:convert')]
class WebPConversionCommand extends Command
{
    protected const CONVERTABLE_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
    ];

    public function __construct(
        private readonly int $quality,
        private readonly string $projectDir,
    ) {
        parent::__construct();
    }

    protected function configure(): void
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
            $io->error('no directories passed');

            return 1;
        }

        $stopwatch = new Stopwatch();

        $stopwatch->start('WebP_transforms');

        $images = [];

        array_walk($directories, function ($directory) use (&$images) {
            $fullPath = $directory[0] === '/' ?: "{$this->projectDir}/{$directory}";
            $images = $this->scanDirs($fullPath);
        });

        if ($input->getOption('create')) {
            $progressBar = new ProgressBar($output, count($images));
            $progressBar->start();

            $errors = [];
            array_walk($images, static function ($image) use ($progressBar, &$errors, $input) {
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
        $io->text("Time : " . $event);

        return 0;
    }

    private function scanDirs(string $fullPath): array
    {
        $finder = new Finder();
        $elements = [];
        $finder->files()->in($fullPath);
        $mimeTypeFinder = new FileinfoMimeTypeGuesser();
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $type = $mimeTypeFinder->guessMimeType($file->getPathname());
                if (!in_array($type, self::CONVERTABLE_MIME_TYPES, true)) {
                    continue;
                }
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
