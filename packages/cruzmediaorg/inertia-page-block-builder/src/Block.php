<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder;

abstract class Block
{
    public string $name;
    public static string $reference;
    public array $data;

    abstract public function options(): array;
    abstract public function render(): string;
}