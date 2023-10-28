<?php

namespace RuangDeveloper\LivewireTable\Traits;

use RuangDeveloper\LivewireTable\Traits\WithPagination;
use RuangDeveloper\LivewireTable\Traits\WithBulkAction;

trait WithSearching
{
    public $LTsearch = '';

    public function getSearchInputPlaceholder(): string
    {
        return 'Search...';
    }

    public function getSearchLabel(): ?string
    {
        return 'Search';
    }

    public function updatedWithSearching($name, $value)
    {
        if (str_starts_with($name, 'LTsearch')) {
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
    }

    public function enableSearchQueryString(): bool
    {
        return false;
    }

    public function getSearchKeyword(): string
    {
        return $this->LTsearch;
    }

    protected function queryStringWithSearching()
    {
        if (!$this->enableSearchQueryString()) return [];

        return [
            'LTsearch' => [
                'as' => 'search',
            ],
        ];
    }
}
