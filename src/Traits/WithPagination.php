<?php

namespace RuangDeveloper\LivewireTable\Traits;

use Livewire\WithPagination as LivewireWithPagination;
use RuangDeveloper\LivewireTable\Traits\WithBulkAction;

trait WithPagination
{
    use LivewireWithPagination;

    public $LTperPage = null;

    public function mountWithPagination()
    {
        $this->LTperPage = $this->LTperPage ?? $this->getPerPage();
    }

    public function updatedWithPagination($name, $value)
    {
        if (str_starts_with($name, 'LTperPage')) {
            $this->resetPage();
            if (in_array(WithBulkAction::class, class_uses($this))) {
                $this->reset([
                    'LTselectedItems',
                    'LTisAllSelected',
                    'LTselectedBulkAction',
                ]);
            }
        }
    }

    public function getPerPageOptions(): array
    {
        return [10, 25, 50, 100];
    }

    public function getPerPage(?int $default = null): int
    {
        if ($this->LTperPage === null) {
            return $default ?? 10;
        }

        return $this->LTperPage;
    }

    protected function queryStringWithPagination()
    {
        return [
            'LTperPage' => [
                'as' => 'per_page',
                'except' => '',
            ],
        ];
    }
}
