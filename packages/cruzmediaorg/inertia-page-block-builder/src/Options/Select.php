<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Options;

class Select extends Option
{
    protected array $options = [];

    public function toArray(): array
    {
        return [
            'type' => 'select',
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
        $this->options = array_map(function ($text, $value) {
            return ['value' => $value, 'text' => $text];
        }, $options, array_keys($options));
        return $this;
    }
}