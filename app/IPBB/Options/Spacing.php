<?php

namespace App\IPBB\Options;

use Cruzmediaorg\InertiaPageBlockBuilder\Options\Option;

class Spacing extends Option
{
    protected array $sides = ['Top', 'Right', 'Bottom', 'Left'];
    protected array $values = [];

    public function toArray(): array
    {
        return [
            'type' => 'spacing',
            'label' => $this->label,
            'name' => $this->name,
            'attributes' => $this->attributes,
            'componentPath' => 'test',
            'values' => $this->values,
        ];
    }

    public static function make(string $label, string $name, array $attributes = [], ?string $componentPath = null): self
    {
        $instance = new self($label, $name, $attributes, $componentPath);
        $instance->initializeValues();
        return $instance;
    }

    protected function initializeValues(): void
    {
        foreach ($this->sides as $side) {
            $this->values[$side] = '0px';
        }
    }

    public function values(array $values): self
    {
        foreach ($values as $side => $value) {
            if (in_array($side, $this->sides)) {
                $this->values[$side] = $value;
            }
        }
        return $this;
    }
}