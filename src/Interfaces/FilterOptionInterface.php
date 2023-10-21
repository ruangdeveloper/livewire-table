<?php

namespace RuangDeveloper\LivewireTable\Interfaces;

interface FilterOptionInterface
{
    public function setValue(string|bool|int $value): FilterOptionInterface;
    public function setLabel(string $label): FilterOptionInterface;
    public function getValue(): string|bool|int;
    public function getLabel(): string;
}
