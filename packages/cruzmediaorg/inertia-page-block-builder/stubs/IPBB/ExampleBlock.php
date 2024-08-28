<?php

namespace App\IPBB;

use Cruzmediaorg\InertiaPageBlockBuilder\Block;

class ExampleBlock extends Block
{
    public string $name = 'Example';

    public static string $reference = 'ipbb.example';

    public array $data = [
        'title' => 'A default title',
        'subtitle' => 'A default subtitle',
        'backgroundColor' => '#000000',
    ];

    public function options(): string
    {
        return 'Example/Options';
    }

    public function render(): string
    {
        return 'Example/Render';
    }
}