<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class MakeBlockOptionCommand extends Command
{
    protected $signature = 'make:option {name : The name of the block option} {--path= : The path for the Vue component}';
    protected $description = 'Create a new IPBB block option';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $studlyName = Str::studly($name);
        $componentPath = $this->option('path') ?? "js/IPBB/Options/{$studlyName}Option.vue";

        if ($this->files->exists(app_path("IPBB/Options/{$studlyName}.php"))) {
            $this->error("The block option {$studlyName} already exists!");
            return;
        }

        $this->createBlockOptionClass($studlyName, $componentPath);
        $this->createOptionComponent($studlyName, $componentPath);

        $this->info("Block option {$studlyName} created successfully!");
        $this->info("Don't forget to register the block option in your block class.");
    }

    protected function createBlockOptionClass($name, $componentPath)
    {
        $stub = $this->files->get(__DIR__.'/stubs/BlockOption.stub');
        $content = str_replace(
            ['{{name}}', '{{lowername}}', '{{componentPath}}'],
            [$name, Str::lower($name), $componentPath],
            $stub
        );
        
        $path = app_path("IPBB/Options/{$name}.php");
        $this->makeDirectory(dirname($path));
        $this->files->put($path, $content);
    }

    protected function createOptionComponent($name, $componentPath)
    {
        $stub = $this->files->get(__DIR__.'/stubs/Option.stub');
        $content = str_replace('{{name}}', $name, $stub);
        
        $path = resource_path($componentPath);
        $this->makeDirectory(dirname($path));
        $this->files->put($path, $content);
    }

    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0755, true, true);
        }
    }
}