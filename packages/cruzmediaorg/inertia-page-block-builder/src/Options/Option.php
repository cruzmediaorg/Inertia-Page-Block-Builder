<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Options;

abstract class Option
{
    public function __construct(
        public string $label,
        public string $name,
        public array $attributes = []
    ) {}

    abstract public function toArray(): array;
}