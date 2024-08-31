<?php

namespace App\IPBB\Options;

use Cruzmediaorg\InertiaPageBlockBuilder\Options\Option;

class IconRadioSelector extends Option
{
    protected array $options = [];

    public function toArray(): array
    {
        return [
            'type' => 'iconradioselector',
            'label' => $this->label,
            'name' => $this->name,
            'attributes' => $this->attributes,
            'componentPath' => $this->getComponentPath(),
            'options' => $this->options,
        ];
    }

    public static function make(string $label, string $name, array $attributes = [], ?string $componentPath = null): self
    {
        return new self($label, $name, $attributes, $componentPath);
    }

    public function options(array $options): self
    {
        $this->options = $options;
        return $this;
    }
}