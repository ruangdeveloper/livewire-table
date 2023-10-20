<?php

namespace RuangDeveloper\LivewireTable\Components;

class FilterOption
{
    protected string $value;
    protected string $label;

    private function __construct(string $value, string $label)
    {
        $this->value = $value;
        $this->label = $label;
    }

    public static function make(string $value, string $label): FilterOption
    {
        return new static($value, $label);
    }

    public function getValue(): string
    {
        if (str_contains($this->value, ':')) {
            throw new \Exception('Filter option value cannot contain colon');
        }
        return $this->value;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
