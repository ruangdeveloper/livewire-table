<?php

namespace RuangDeveloper\LivewireTable\Traits;

use Livewire\Attributes\Url;
use RuangDeveloper\LivewireTable\Components\Column;

trait WithColumnSelection
{
    #[Url(as: 'columns', keep: true)]
    public $LTSelectedColumnsString = '';

    public $LTSelectedColumns = [];

    public function mountWithColumnSelection(): void
    {
        if (empty($this->LTSelectedColumnsString)) {
            $this->LTSelectedColumns = collect($this->columns())
                ->filter(function (Column $column) {
                    return $column->isHidden() === false;
                })
                ->map(function (Column $column) {
                    return $column->getName();
                })->toArray();

            $this->LTSelectedColumnsString = implode(',', $this->LTSelectedColumns);
        } else {
            $this->LTSelectedColumns = explode(',', $this->LTSelectedColumnsString);
        }
    }

    public function getSelectedColumns(): array
    {
        return collect($this->columns())
            ->filter(function (Column $column) {
                return in_array($column->getName(), $this->LTSelectedColumns) && $column->isHidden() === false;
            })
            ->toArray();
    }

    public function getUnselectedColumns(): array
    {
        return collect($this->columns())
            ->filter(function (Column $column) {
                return !in_array($column->getName(), $this->LTSelectedColumns) && $column->isHidden() === false;
            })->toArray();
    }

    public function selectColumn(string $name): void
    {
        $this->LTSelectedColumns[] = $name;
        $this->LTSelectedColumnsString = implode(',', $this->LTSelectedColumns);
    }

    public function removeColumn(string $name): void
    {
        $this->LTSelectedColumns = collect($this->LTSelectedColumns)
            ->filter(fn (string $columnName) => $columnName !== $name)->toArray();

        if ($this->getSortBy() === $name) {
            $this->reset([
                'LTsortBy',
                'LTsortDirection',
            ]);
        }
        $this->LTSelectedColumnsString = implode(',', $this->LTSelectedColumns);
    }
}
