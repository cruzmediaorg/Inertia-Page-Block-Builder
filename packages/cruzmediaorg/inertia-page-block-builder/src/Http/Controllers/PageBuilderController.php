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
        return Inertia::render('IPBB/PageBuilder', [
            'registeredBlocks' => $registeredBlocks,
        ]);
    }
}