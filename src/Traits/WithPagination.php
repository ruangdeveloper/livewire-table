<?php

namespace RuangDeveloper\LivewireTable\Traits;

use Livewire\Attributes\Url;
use Livewire\WithPagination as LivewireWithPagination;

trait WithPagination
{
    use LivewireWithPagination;

    #[Url(as: 'per_page')]
    public $LTperPage = 10;

    public function updatedLTperPage(): void
    {
        $this->resetPage();
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
}
