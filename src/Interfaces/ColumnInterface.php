<?php

namespace RuangDeveloper\LivewireTable\Interfaces;

use Closure;

interface ColumnInterface
{
    public function setSortable(bool $sortable = true): ColumnInterface;
    public function setRenderer(Closure $render): ColumnInterface;
    public function setExportRenderer(Closure $exportRender): ColumnInterface;
    public function setHidden(bool $hidden = false): ColumnInterface;
    public function setName(string $name): ColumnInterface;
    public function setLabel(string $label): ColumnInterface;
    public function getName(): string;
    public function getLabel(): string;
    public function getRenderer(): Closure;
    public function getExportRenderer(): Closure;
    public function isSortable(): bool;
    public function isHidden(): bool;
}
