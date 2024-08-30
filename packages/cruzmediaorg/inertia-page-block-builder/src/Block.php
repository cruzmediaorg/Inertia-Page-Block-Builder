<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder;

abstract class Block
{
    public string $name;
    public static string $reference;
    public array $data;
    public ?string $containerId = null; 

    abstract public function options(): array;
    abstract public function render(): string;

    public function setContainerId(?string $containerId): void
    {
        $this->containerId = $containerId;
    }

    public function getContainerId(): ?string
    {
        return $this->containerId;
    }
}