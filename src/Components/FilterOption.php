<?php

namespace RuangDeveloper\LivewireTable\Components;

use RuangDeveloper\LivewireTable\Interfaces\FilterOptionInterface;

class FilterOption implements FilterOptionInterface
{
    private string $value;
    private string $label;

    private function __construct(string $value, string $label)
    {
        $this->value = $value;
        $this->label = $label;
    }

    public static function make(string $value, string $label): self
    {
        return new static($value, $label);
    }

    public function setValue(string|bool|int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
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
