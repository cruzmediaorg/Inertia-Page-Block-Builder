<?php

namespace App\IPBB;

use App\IPBB\Options\ColorPicker;
use App\IPBB\Options\Select;
use Cruzmediaorg\InertiaPageBlockBuilder\Block;
use Cruzmediaorg\InertiaPageBlockBuilder\Options\Input;
use Cruzmediaorg\InertiaPageBlockBuilder\Options\Textarea;

class MegaCustomizableHeaderBlock extends Block
{
    public string $name = 'MegaCustomizableHeader';

    public static string $reference = 'ipbb.megacustomizableheader';

    public array $data = [
        'title' => 'Welcome to Our Website',
        'subtitle' => 'Discover Amazing Features and Services',
        'content' => 'We offer innovative solutions to meet your needs. Our team of experts is dedicated to providing top-notch service and support.',
        'titleFontSize' => '48px',
        'subtitleFontSize' => '24px',
        'contentFontSize' => '18px',
        'titleFontWeight' => '700',
        'subtitleFontWeight' => '400',
        'contentFontWeight' => '400',
        'fontFamily' => 'Arial, sans-serif',
        'backgroundColor' => '#f8f9fa',
        'textColor' => '#333333',
        'textAlignment' => 'left',
    ];

    public function options(): array
    {
        return [
            Input::make('Title', 'title'),
            Input::make('Subtitle', 'subtitle'),
            Textarea::make('Content', 'content'),
            Select::make('Title Font Size', 'titleFontSize')
                ->options(['36px', '48px', '60px', '72px']),
            Select::make('Subtitle Font Size', 'subtitleFontSize')
                ->options(['18px', '24px', '30px', '36px']),
            Select::make('Content Font Size', 'contentFontSize')
                ->options(['16px', '18px', '20px', '22px']),
            Select::make('Title Font Weight', 'titleFontWeight')
                ->options(['300', '400', '500', '600', '700', '800']),
            Select::make('Subtitle Font Weight', 'subtitleFontWeight')
                ->options(['300', '400', '500', '600', '700', '800']),
            Select::make('Content Font Weight', 'contentFontWeight')
                ->options(['300', '400', '500', '600', '700', '800']),
            Select::make('Font Family', 'fontFamily')
                ->options([
                    'Arial, sans-serif',
                    'Helvetica, sans-serif',
                    'Georgia, serif',
                    'Times New Roman, serif',
                    'Courier New, monospace',
                ]),
            Select::make('Text Alignment', 'textAlignment')
                ->options(['left', 'center', 'right', 'justify']),
            ColorPicker::make('Background Color', 'backgroundColor'),
            ColorPicker::make('Text Color', 'textColor'),
        ];
    }

    public function render(): string
    {
        return 'MegaCustomizableHeader/Render';
    }
}