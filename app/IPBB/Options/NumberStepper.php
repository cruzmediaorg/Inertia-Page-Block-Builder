<?php

namespace App\IPBB\Options;

use Cruzmediaorg\InertiaPageBlockBuilder\Options\Option;

class NumberStepper extends Option
{

    protected int $step = 1;
    protected int $min = 0;
    protected int $max = 100;
    protected string $append = '';

    public function toArray(): array
    {
        return [
            'type' => 'numberstepper',
            'label' => $this->label,
            'name' => $this->name,
            'attributes' => $this->attributes,
            'componentPath' => $this->getComponentPath(),
            'step' => $this->step,
            'min' => $this->min,
            'max' => $this->max,
            'append' => $this->append,
        ];
    }

    public static function make(string $label, string $name, array $attributes = [], ?string $componentPath = null): self
    {
        return new self($label, $name, $attributes, $componentPath);
    }

    public function step(int $step): self
    {
        $this->step = $step;
        return $this;
    }

    public function min(int $min): self
    {
        $this->min = $min;
        return $this;
    }

    public function max(int $max): self
    {
        $this->max = $max;
        return $this;
    }

    public function append(string $append): self
    {
        $this->append = $append;
        return $this;
    }
}