<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Options;

class Input extends Option
{
    public function __construct(string $label, string $name, array $attributes = [])
    {
        parent::__construct($label, $name, $attributes);
        $this->setComponentPath('./options/InputOption.vue');
    }

    public function toArray(): array
    {
        return [
            'type' => 'input',
            'label' => $this->label,
            'name' => $this->name,
            'attributes' => array_merge(['type' => 'text'], $this->attributes),
            'componentPath' => $this->getComponentPath(),
        ];
    }

    public static function make(string $label, string $name, array $attributes = []): self
    {
        return new self($label, $name, $attributes);
    }
}