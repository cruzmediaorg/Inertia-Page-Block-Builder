<?php

namespace App\IPBB;

use Cruzmediaorg\InertiaPageBlockBuilder\Block;

class ExampleBlock extends Block
{
    public function options(): array
    {
        return [
            'title' => [
                'type' => 'string',
                'default' => 'Example Block',
            ],
        ];
    }

    public function render(): string
    {
        return 'IPBB/Blocks/ExampleBlock';
    }
}