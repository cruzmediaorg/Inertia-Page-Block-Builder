<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cruzmediaorg\InertiaPageBlockBuilder\BlockManager;

class IPBBServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->afterResolving(BlockManager::class, function (BlockManager $blockManager) {
            // Register your blocks here
            $blockManager->registerBlock(\App\IPBB\ExampleBlock::class);
        });
    }
}