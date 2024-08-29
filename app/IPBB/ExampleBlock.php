<?php

namespace App\IPBB;

use Cruzmediaorg\InertiaPageBlockBuilder\Block;
use Cruzmediaorg\InertiaPageBlockBuilder\Options\Input;
use Cruzmediaorg\InertiaPageBlockBuilder\Options\Textarea;

class ExampleBlock extends Block
{
    public string $name = 'Example';

    public static string $reference = 'ipbb.example';

    public array $data = [
        'title' => 'A default title',
        'content' => 'Default content',
        'backgroundColor' => '#000000',
    ];

    public function options(): array
    {
        return [
            Input::make('Title', 'title'),
            Textarea::make('Content', 'content'),
            Input::make('Background Color', 'backgroundColor', ['type' => 'color']),
        ];
    }

    public function render(): string
    {
        return 'Example/Render';
    }
}