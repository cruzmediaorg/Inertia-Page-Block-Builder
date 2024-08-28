<?php

namespace Cruzmediaorg\InertiaPageBlockBuilder\Options;

abstract class Option
{
    protected string $path;

    public function __construct(
        public string $label,
        public string $name,
        public array $attributes = []
    ) {
        $this->path = $this->getDefaultPath();
    }

    abstract public function toArray(): array;

    public function getComponentPath(): string
    {
        return $this->path;
    }

    public function setComponentPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    protected function getDefaultPath(): string
    {
        $className = class_basename($this);
        $optionName = str_replace('Option', '', $className);
        return "./IPBB/Options/{$optionName}Option.vue";
    }
}