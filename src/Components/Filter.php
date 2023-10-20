<?php

namespace RuangDeveloper\LivewireTable\Components;

class Filter
{
    protected string $name;
    protected ?string $label;
    protected array $filterOptions = [];

    private function __construct(string $name, ?string $label = null)
    {
        $this->name = $name;
        $this->label = $label;
    }

    public static function make(string $name, ?string $label = null): Filter
    {
        return new static($name, $label);
    }

    public function filterOptions(array $filterOptions): Filter
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
