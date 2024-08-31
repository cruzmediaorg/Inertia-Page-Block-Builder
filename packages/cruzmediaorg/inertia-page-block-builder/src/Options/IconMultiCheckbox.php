<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Options;

class IconMultiCheckbox extends Option
{
    protected array $options = [];

    public function toArray(): array
    {
        return [
            'type' => 'iconmulticheckbox',
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