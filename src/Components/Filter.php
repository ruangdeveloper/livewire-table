<?php

namespace RuangDeveloper\LivewireTable\Components;

use RuangDeveloper\LivewireTable\Interfaces\FilterInterface;

class Filter implements FilterInterface
{
    private string $name;
    private ?string $label;
    private array $filterOptions = [];

    private function __construct(string $name, ?string $label = null)
    {
        $this->name = $name;
        $this->label = $label;
    }

    public static function make(string $name, ?string $label = null): self
    {
        return new static($name, $label);
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setLabel(?string $label = null): self
    {
        $this->label = $label;

        return $this;
    }

    public function setFilterOptions(array $filterOptions): self
    {
        $this->filterOptions = $filterOptions;

        return $this;
    }

    public function getName(): string
    {
        if (str_contains($this->name, ':')) {
            throw new \Exception('Filter name cannot contain colon');
        }
        return $this->name;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getFilterOptions(): array
    {
        return $this->filterOptions;
    }
}
