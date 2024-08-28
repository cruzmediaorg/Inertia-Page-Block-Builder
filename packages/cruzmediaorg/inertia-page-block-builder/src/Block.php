<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder;

abstract class Block
{
    public string $name;
    public static string $reference;
    public array $data;

    abstract public function options(): string;
    abstract public function render(): string;
}