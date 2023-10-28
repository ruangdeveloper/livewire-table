<?php

namespace RuangDeveloper\LivewireTable\Components;

use Closure;
use RuangDeveloper\LivewireTable\Interfaces\ColumnInterface;

class Column implements ColumnInterface
{
    private string $name;
    private string $label;
    private bool $sortable = false;
    private Closure $renderer;
    private Closure $exportRenderer;
    private bool $hidden = false;
    private bool $hiddenOnExport = false;

    public function __construct(string $name, string $label)
    {
        $this->name = $name;
        $this->label = $label;
    }

    public static function make(string $name, string $label): self
    {
        return new static($name, $label);
    }

    public function setSortable(bool $sortable = true): self
    {
        $this->sortable = $sortable;

        return $this;
    }

    public function setRenderer(Closure $renderer): self
    {
        $this->renderer = $renderer;
        $this->exportRenderer = $renderer;

        return $this;
    }

    public function setExportRenderer(Closure $exportRenderer): self
    {
        $this->exportRenderer = $exportRenderer;

        return $this;
    }

    public function setHidden(bool $hidden = false): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function setHiddenOnExport(bool $hiddenOnExport = false): self
    {
        $this->hiddenOnExport = $hiddenOnExport;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function getRenderer(): Closure
    {
        return $this->renderer;
    }

    public function getExportRenderer(): Closure
    {
        return $this->exportRenderer;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function isHiddenOnExport(): bool
    {
        return $this->hiddenOnExport;
    }
}
