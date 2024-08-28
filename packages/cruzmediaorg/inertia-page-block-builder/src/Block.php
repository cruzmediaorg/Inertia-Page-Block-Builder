<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder;

abstract class Block
{
    abstract public function options(): array;
    abstract public function render(): string;

    public function name(): string
    {
        return class_basename($this);
    }
}