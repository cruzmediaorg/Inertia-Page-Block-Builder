<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class MakeBlockCommand extends Command
{
    protected $signature = 'make:block {name : The name of the block}';
    protected $description = 'Create a new IPBB block';

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

        $this->createBlockClass($studlyName);
        $this->createOptionsComponent($studlyName);
        $this->createRenderComponent($studlyName);

        $this->info("Block {$studlyName} created successfully!");
    }

    protected function createBlockClass($name)
    {
        $stub = $this->files->get(__DIR__.'/stubs/Block.stub');
        $content = str_replace(['{{name}}', '{{lowername}}'], [$name, Str::lower($name)], $stub);
        
        $path = app_path("IPBB/{$name}Block.php");
        $this->makeDirectory(dirname($path));
        $this->files->put($path, $content);
    }

    protected function createOptionsComponent($name)
    {
        $stub = $this->files->get(__DIR__.'/stubs/Options.stub');
        $content = str_replace('{{name}}', $name, $stub);
        
        $path = resource_path("js/{$name}/Options.vue");
        $this->makeDirectory(dirname($path));
        $this->files->put($path, $content);
    }

    protected function createRenderComponent($name)
    {
        $stub = $this->files->get(__DIR__.'/stubs/Render.stub');
        $content = str_replace('{{name}}', $name, $stub);
        
        $path = resource_path("js/{$name}/Render.vue");
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