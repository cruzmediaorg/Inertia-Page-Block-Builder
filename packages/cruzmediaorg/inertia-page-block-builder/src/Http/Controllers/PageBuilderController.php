<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Http\Controllers;

use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Cruzmediaorg\InertiaPageBlockBuilder\BlockManager;

class PageBuilderController extends Controller
{
    protected $blockManager;

    public function __construct(BlockManager $blockManager)
    {
        $this->blockManager = $blockManager;
    }

    public function index()
    {
        $registeredBlocks = $this->blockManager->getRegisteredBlocks();
        
        // Transform the associative array into a numeric array
        $transformedBlocks = array_values(array_map(function ($key, $block) {
            return [
                'name' => $block['name'],
                'options' => $block['options'],
                'render' => $block['render'],
                'data' => $block['data'],
                'icon' => $block['icon'],
            ];
        }, array_keys($registeredBlocks), $registeredBlocks));

        return Inertia::render('IPBB/PageBuilder', [
            'registeredBlocks' => $transformedBlocks,
        ]);
    }
}