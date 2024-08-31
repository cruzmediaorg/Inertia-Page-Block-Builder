<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder;

use App\IPBB\Options\Spacing;

abstract class Block
{
    public string $name;
    public static string $reference;
    public array $data;
    public ?string $containerId = null;
    public string $icon;

    protected array $defaultAttributes = [
        'padding' => [
            'top' => '0px',
            'right' => '0px',
            'bottom' => '0px',
            'left' => '0px',
        ],
        'margin' => [
            'top' => '0px',
            'right' => '0px',
            'bottom' => '0px',
            'left' => '0px',
        ],
    ];

    protected function options()
    {
        return [
            Spacing::make('Padding', 'padding')
                ->values($this->defaultAttributes['padding']),
            Spacing::make('Margin', 'margin')
                ->values($this->defaultAttributes['margin']),
        ];
    }

    abstract public function render(): string;

}