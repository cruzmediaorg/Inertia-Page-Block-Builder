<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Options;

class Input extends Option
{
    public function toArray(): array
    {
        return [
            'type' => 'input',
            'label' => $this->label,
            'name' => $this->name,
            'attributes' => array_merge(['type' => 'text'], $this->attributes),
        ];
    }

    public static function make(string $label, string $name, array $attributes = []): self
    {
        return new self($label, $name, $attributes);
    }
}