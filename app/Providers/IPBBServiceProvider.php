<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cruzmediaorg\InertiaPageBlockBuilder\BlockManager;
use App\IPBB\ExampleBlock;
use App\IPBB\MegaCustomizableHeaderBlock;
use App\IPBB\TextBlock;

class IPBBServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->afterResolving(BlockManager::class, function (BlockManager $blockManager) {
            // Register your blocks here
            $blockManager->registerBlock(TextBlock::class);
        });
    }
}