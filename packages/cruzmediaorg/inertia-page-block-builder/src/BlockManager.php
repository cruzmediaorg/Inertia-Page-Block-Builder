<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder;

class BlockManager
{
    protected $blocks = [];

    public function registerBlock(string $blockClass)
    {
        $block = new $blockClass();
        if (!$block instanceof Block) {
            throw new \InvalidArgumentException("Class {$blockClass} must extend " . Block::class);
        }
        $this->blocks[$block::$reference] = $blockClass;
    }

    public function getRegisteredBlocks(): array
    {
        return array_map(function ($blockClass) {
            $block = new $blockClass();
            return [
                'name' => $block->name,
                'reference' => $block::$reference,
                'data' => $block->data,
                'options' => $block->options(),
                'render' => $block->render(),
            ];
        }, $this->blocks);
    }
}