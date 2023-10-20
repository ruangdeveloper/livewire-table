<?php

namespace RuangDeveloper\LivewireTable\Traits;

use Livewire\Attributes\Url;

trait WithSorting
{
    #[Url(as: 'sort')]
    public $LTsortBy = '';

    #[Url(as: 'direction')]
    public $LTsortDirection = '';

    public function sort(string $sortBy): void
    {
        if ($this->LTsortBy === $sortBy) {
            if ($this->LTsortDirection === 'asc') {
                $this->LTsortDirection = 'desc';
            } else {
                $this->LTsortBy = '';
                $this->LTsortDirection = '';
            }
        } else {
            $this->LTsortBy = $sortBy;
            $this->LTsortDirection = 'asc';
        }

        $this->resetPage();
        $this->reset([
            'LTselectedItems',
            'LTisAllSelected',
            'LTselectedBulkAction'
        ]);
    }

    protected function getSortBy(): string
    {
        return $this->LTsortBy;
    }

    protected function getSortDirection(): string
    {
        return $this->LTsortDirection;
    }
}
