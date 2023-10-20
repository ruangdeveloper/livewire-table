<?php

namespace RuangDeveloper\LivewireTable\Components;

use Closure;

class Column
{
    protected string $name;
    protected string $label;
    protected bool $sortable = false;
    protected ?Closure $render = null;
    protected ?Closure $exportRender = null;
    protected bool $hidden = false;

    private function __construct(string $name, string $label)
    {
        $this->name = $name;
        $this->label = $label;
        $this->sortable = false;
    }

    public static function make(string $name, string $label): Column
    {
        return new static($name, $label);
    }

    public function sortable(bool $sortable = true): Column
    {
        $this->sortable = $sortable;

        return $this;
    }

    public function render(Closure $render): Column
    {
        $this->render = $render;

        return $this;
    }

    public function exportRender(Closure $exportRender): Column
    {
        $this->exportRender = $exportRender;

        return $this;
    }

    public function hidden(bool $hidden = false): Column
    {
        $this->hidden = $hidden;

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

    public function getRenderer(): ?Closure
    {
        return $this->render;
    }

    public function getExportRenderer(): ?Closure
    {
        if ($this->exportRender) {
            return $this->exportRender;
        }

        return $this->render;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }
}
