<?php

namespace RuangDeveloper\LivewireTable\Traits;

use Livewire\Attributes\Url;

trait WithSearching
{
    #[Url(as: 'search')]
    public $LTsearch = '';

    public function searchInputPlaceholder(): string
    {
        return 'Search...';
    }

    public function searchLabel(): ?string
    {
        return 'Search';
    }

    public function updatedLTSearch()
    {
        $this->resetPage();
        if (in_array(WithBulkAction::class, class_uses($this))) {
            $this->reset([
                'LTselectedItems',
                'LTisAllSelected',
                'LTselectedBulkAction',
            ]);
        }
    }

    protected function getSearchKeyword(): string
    {
        return $this->LTsearch;
    }
}
