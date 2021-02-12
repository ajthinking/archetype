<?php

namespace Archetype\Commands;

use Illuminate\Console\Command;
use PHPFile;
use Archetype\Support\Exceptions\NotImplementedYetException;

class DemoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archetype:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Provides a few interactive examples to illustrate archetype capabilities';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $demos = [
            ['stats', 'Get some stats on your work'],
            ['relationships', 'Add missing relationship methods'],
            ['softdeletes', 'Use soft deletes on your User model'],
            ['installer', 'How to make an installer for your package'],
        ];

        $names = collect($demos)->map(function ($demo) {
            return $demo[0];
        })->toArray();

        $this->table(['Demo', 'Description'], $demos);

        $method = $this->choice('Select a demo >>', $names, 0);

        if ($method != 'stats') {
            throw new NotImplementedYetException('Coming soon');
        }

        $this->$method();
    }

    public function stats()
    {
        $files = PHPFile::in('')->get();

        $fileCount = $files->count();

        $charCount = $files->sum(function ($file) {
            return strlen($file->contents());
        });

        $classes = $files->filter(function ($file) {
            return $file->className();
        })->map(function ($file) {
            return $file->className();
        });

        $classCount = $classes->unique()->count();

        $nonClassCount = $fileCount - $classCount;

        $extendingModel = PHPFile::where('extends', 'Model')->get()->count();

        $extendingController = PHPFile::where('extends', 'Controller')->get()->count();

        $this->table(["Stats (excluding vendor folder)"], [
            ["$fileCount PHP files."],
            ["$charCount characters worth of PHP code."],
            ["$classCount classes"],
            ["$extendingModel classes extending name 'Model'"],
            ["$extendingController classes extending name 'Controller'"],
            ["$nonClassCount non class files"],
        ]);

        $this->presentPathToSource();
    }

    public function presentPathToSource()
    {
        $this->line('');
        $this->line('Review source: ' .
            base_path('vendor/ajthinking/archetype/src/Commands/DemoCommand.php'));
    }
}
