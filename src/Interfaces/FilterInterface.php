<?php

namespace RuangDeveloper\LivewireTable\Interfaces;

interface FilterInterface
{
    public function setFilterOptions(array $filterOptions): FilterInterface;
    public function setName(string $name): FilterInterface;
    public function setLabel(?string $label = null): FilterInterface;
    public function getFilterOptions(): array;
    public function getName(): string;
    public function getLabel(): ?string;
}
