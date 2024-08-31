<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Options;

class ColorPicker extends Option
{
    public function toArray(): array
    {
        return [
            'type' => 'colorpicker',
            'label' => $this->label,
            'name' => $this->name,
            'attributes' => $this->attributes,
            'componentPath' => $this->getComponentPath(),
        ];
    }

    public static function make(string $label, string $name, array $attributes = [], ?string $componentPath = null): self
    {
        return new self($label, $name, $attributes, $componentPath);
    }
}