<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Blocks;

use Cruzmediaorg\InertiaPageBlockBuilder\Block;
use Cruzmediaorg\InertiaPageBlockBuilder\Options\ColorPicker;
use Cruzmediaorg\InertiaPageBlockBuilder\Options\IconMultiCheckbox;
use Cruzmediaorg\InertiaPageBlockBuilder\Options\IconRadioSelector;
use Cruzmediaorg\InertiaPageBlockBuilder\Options\NumberStepper;
use Cruzmediaorg\InertiaPageBlockBuilder\Options\Select;
use Cruzmediaorg\InertiaPageBlockBuilder\Options\Input;

class TextBlock extends Block
{
    public string $name = 'Text';
    public static string $reference = 'ipbb.text';
    public string $icon = 'fa-solid fa-align-left';
    public array $data;

    public function __construct()
    {
        $this->data = array_merge($this->defaultAttributes, [
            'value' => 'Text',
            'type' => 'p',
            'fontSize' => '16px',
            'fontFamily' => 'Arial, sans-serif',
            'color' => '#000000',
            'backgroundColor' => 'transparent',
            'textAlign' => 'left',
            'textDecoration' => 'none',
            'textTransform' => 'none',
            'fontStyle' => [
                'bold' => false,
                'italic' => false,
                'underline' => false,
                'overline' => false,
                'line-through' => false,
            ]
    ]);
    }

    public function options(): array
    {
        return array_merge([
            Input::make('Text', 'value'),
            NumberStepper::make('Font Size', 'fontSize')
                ->min(12)
                ->max(60)
                ->step(2)
                ->append('px'),
            Select::make('Font Family', 'fontFamily')
                ->options(['Arial, sans-serif' => 'Arial', 'Helvetica, sans-serif' => 'Helvetica', 'Times New Roman, serif' => 'Times New Roman', 'Courier New, monospace' => 'Courier New']),
            IconMultiCheckbox::make('Font Style', 'fontStyle')
                ->options([
                    ['value' => 'bold', 'icon' => 'fa-solid fa-bold'],
                    ['value' => 'italic', 'icon' => 'fa-solid fa-italic'],
                    ['value' => 'underline', 'icon' => 'fa-solid fa-underline'],
                ]),
            ColorPicker::make('Color', 'color'),
            ColorPicker::make('Background Color', 'backgroundColor'),
            IconRadioSelector::make('Text Align', 'textAlign')
                ->options([
                    ['value' => 'left', 'icon' => 'fa-solid fa-align-left'],
                    ['value' => 'center', 'icon' => 'fa-solid fa-align-center'],
                    ['value' => 'right', 'icon' => 'fa-solid fa-align-right'],
                ]),
            Select::make('Text Transform', 'textTransform')
                ->options(['none' => 'None', 'uppercase' => 'Uppercase', 'lowercase' => 'Lowercase', 'capitalize' => 'Capitalize']),
                Select::make('Render as', 'type')
                ->options(['h1' => 'Heading 1', 'h2' => 'Heading 2', 'h3' => 'Heading 3', 'p' => 'Paragraph']),
        ], parent::options());
    }

    public function render(): string
    {
        return 'Text/Render';
    }
}