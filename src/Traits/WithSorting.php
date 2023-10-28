<?php

namespace RuangDeveloper\LivewireTable\Traits;

use RuangDeveloper\LivewireTable\Traits\WithBulkAction;
use RuangDeveloper\LivewireTable\Traits\WithPagination;

trait WithSorting
{
    public $LTsortBy = '';
    public $LTsortDirection = '';

    public function sort(string $sortBy): void
    {
        if ($this->LTsortBy === $sortBy) {
            if ($this->LTsortDirection === 'asc') {
                $this->LTsortDirection = 'desc';
            } elseif ($this->LTsortDirection === 'desc') {
                $this->LTsortBy = '';
                $this->LTsortDirection = '';
            } else {
                $this->LTsortDirection = 'asc';
            }
        } else {
            $this->LTsortBy = $sortBy;
            $this->LTsortDirection = 'asc';
        }

        if (in_array(WithPagination::class, class_uses($this))) {
            $this->resetPage();
        }

        if (in_array(WithBulkAction::class, class_uses($this))) {
            $this->reset([
                'LTselectedItems',
                'LTisAllSelected',
                'LTselectedBulkAction',
            ]);
        }
    }

    public function enableSoringQueryString(): bool
    {
        return false;
    }

    public function getSortBy(): string
    {
        return $this->LTsortBy;
    }

    public function getSortDirection(): string
    {
        return $this->LTsortDirection;
    }

    protected function queryStringWithSorting()
    {
        if (!$this->enableSoringQueryString()) return [];

        return [
            'LTsortBy' => [
                'as' => 'sort',
                'except' => '',
            ],
            'LTsortDirection' => [
                'as' => 'direction',
                'except' => '',
            ],
        ];
    }
}
