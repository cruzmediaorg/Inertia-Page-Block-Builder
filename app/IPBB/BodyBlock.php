<?php

namespace App\IPBB;

use Cruzmediaorg\InertiaPageBlockBuilder\Block;

class BodyBlock extends Block
{
    public string $name = 'Body';

    public static string $reference = 'ipbb.body';

    public array $data = [
        'title' => 'A default title',
        'content' => 'Default content',
    ];

    public function options(): string
    {
        return 'Body/Options';
    }

    public function render(): string
    {
        return 'Body/Render';
    }
}