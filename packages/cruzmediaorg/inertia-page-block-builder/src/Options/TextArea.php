<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Options;

class TextArea extends Option
{
    public function __construct(string $label, string $name, array $attributes = [])
    {
        parent::__construct($label, $name, $attributes);
        $this->setComponentPath('./options/TextareaOption.vue');
    }

    public function toArray(): array
    {
        return [
            'type' => 'textarea',
            'label' => $this->label,
            'name' => $this->name,
            'attributes' => $this->attributes,
            'componentPath' => $this->getComponentPath(),
        ];
    }

    public static function make(string $label, string $name, array $attributes = []): self
    {
        return new self($label, $name, $attributes);
    }
}