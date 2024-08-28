<?php

namespace App\Providers;

use App\IPBB\BodyBlock;
use App\IPBB\ExampleBlock;
use Illuminate\Support\ServiceProvider;
use Cruzmediaorg\InertiaPageBlockBuilder\BlockManager;
// use App\IPBB\ExampleBlock;


class IPBBServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->afterResolving(BlockManager::class, function (BlockManager $blockManager) {
            // Register your blocks here
            $blockManager->registerBlock(ExampleBlock::class);
            $blockManager->registerBlock(BodyBlock::class);
        });
    }
}