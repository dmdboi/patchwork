<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;


class MakeTheme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:theme {name} {--page} {--component} {--section}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new theme blade view';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $name = $this->argument('name');
        $type = $this->getViewType();

        if(!$type) {
            $this->error('Please specify a type of view to create');
            return;
        }

        $viewDirectory = resource_path("views/theme/{$type}s");

        if(!File::isDirectory($viewDirectory)) {
            File::makeDirectory($viewDirectory, 0755, true);
        }

        $this->info('Creating new theme view...');
        
        $filePath = $viewDirectory . '/' . $name . '.blade.php';

        File::put($filePath,'<p>Hello, World!</p>');

        $this->info('Created file: ' . $filePath);
        $this->info('Don\'t forget to update your theme file in app/Views/Themes');
    }

    private function getViewType()
    {
        if ($this->option('page')) {
            return 'page';
        }

        if ($this->option('component')) {
            return 'component';
        }

        if ($this->option('section')) {
            return 'section';
        }

        return 'page';
    }
}
