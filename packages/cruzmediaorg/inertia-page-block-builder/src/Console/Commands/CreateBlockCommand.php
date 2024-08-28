<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class CreateBlockCommand extends Command
{
    protected $signature = 'ipbb:create-block {name : The name of the block}';
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
        $this->createRenderComponent($studlyName);
        $this->createOptionsComponent($studlyName);

        $this->info("Block {$studlyName} created successfully!");
    }

    protected function createBlockClass($name)
    {
        $stub = $this->files->get(__DIR__.'/stubs/block.stub');
        $content = str_replace('{{name}}', $name, $stub);
        
        $path = app_path("IPBB/Blocks/{$name}.php");
        $this->makeDirectory(dirname($path));
        $this->files->put($path, $content);
    }

    protected function createRenderComponent($name)
    {
        $stub = $this->files->get(__DIR__.'/stubs/render.stub');
        $content = str_replace('{{name}}', $name, $stub);
        
        $path = resource_path("js/IPBB/Blocks/{$name}/Render.vue");
        $this->makeDirectory(dirname($path));
        $this->files->put($path, $content);
    }

    protected function createOptionsComponent($name)
    {
        $stub = $this->files->get(__DIR__.'/stubs/options.stub');
        $content = str_replace('{{name}}', $name, $stub);
        
        $path = resource_path("js/IPBB/Blocks/{$name}/Options.vue");
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