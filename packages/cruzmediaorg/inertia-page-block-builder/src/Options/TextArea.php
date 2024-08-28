<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Options;

class TextArea extends Option
{
    public function toArray(): array
    {
        return [
            'type' => 'textarea',
            'label' => $this->label,
            'name' => $this->name,
            'attributes' => $this->attributes,
        ];
    }

    public static function make(string $label, string $name, array $attributes = []): self
    {
        return new self($label, $name, $attributes);
    }
}