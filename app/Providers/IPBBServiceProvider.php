<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cruzmediaorg\InertiaPageBlockBuilder\BlockManager;
use App\IPBB\ExampleBlock;


class IPBBServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->afterResolving(BlockManager::class, function (BlockManager $blockManager) {
            // Register your blocks here
            $blockManager->registerBlock(ExampleBlock::class);
        });
    }
}