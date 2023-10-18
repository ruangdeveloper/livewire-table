<?php

namespace RuangDeveloper\LivewireTable;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

abstract class LivewireTable extends Component
{
    #[Url(as: 'sort')]
    public $livewireTable__sortBy = '';

    #[Url(as: 'direction')]
    public $livewireTable__sortDirection = '';

    #[Url(as: 'search')]
    public $livewireTable__search = '';

    public array $livewireTable__selectedItems = [];

    public bool $livewireTable__isAllSelected = false;

    public string $livewireTable__selectedBulkAction = '';

    public function columns(): array
    {
        return [];
    }

    public function data(): array|Collection|LengthAwarePaginator
    {
        return [];
    }

    public function noDataMessage(): string
    {
        return 'No data available.';
    }

    public function withSearching(): bool
    {
        return false;
    }

    public function searchInputPlaceholder(): string
    {
        return 'Search...';
    }

    public function withBulkAction(): bool
    {
        return false;
    }

    public function bulkActions(): array
    {
        return [];
    }

    public function bulkActionCheckBoxFiller(): Closure
    {
        return function ($item, $index) {
            return $index;
        };
    }

    public function bulkActionButtonLabel(): string
    {
        return 'Apply';
    }

    public function bulkActionOptionsLabel(): string
    {
        return 'Choose an action...';
    }

    public function getSelectedItems(): array
    {
        return $this->livewireTable__selectedItems;
    }

    public function executeBulkAction()
    {
        $bulkActions = collect($this->bulkActions())->map(function (BulkAction $bulkAction) {
            return [
                'name' => $bulkAction->getName(),
                'handler' => $bulkAction->getHandler(),
            ];
        })->toArray();

        if ($this->livewireTable__selectedBulkAction === '') {
            return;
        }

        foreach ($bulkActions as $bulkAction) {
            if ($bulkAction['name'] === $this->bulkAction) {
                ($bulkAction['handler'])($this->selectedItems);
                $this->reset(['livewireTable__selectedItems', 'livewireTable__isAllSelected', 'livewireTable__selectedBulkAction']);
            }
        }
    }

    public function getSelectedBulkAction(): ?BulkAction
    {
        $bulkActions = $this->bulkActions();

        foreach ($bulkActions as $bulkAction) {
            if ($bulkAction->getName() === $this->livewireTable__selectedBulkAction) {
                return $bulkAction;
            }
        }

        return null;
    }

    public function updated(string $name, mixed $value): void
    {
        if (str_starts_with($name, 'livewireTable__')) {
            if ($name === 'livewireTable__search') {
                $this->resetPage();
                $this->reset([
                    'livewireTable__selectedItems',
                    'livewireTable__isAllSelected',
                    'livewireTable__selectedBulkAction'
                ]);
            }

            if ($name === 'livewireTable__sortBy') {
                $this->resetPage();
                $this->reset([
                    'livewireTable__selectedItems',
                    'livewireTable__isAllSelected',
                    'livewireTable__selectedBulkAction'
                ]);
            }

            if ($name === 'livewireTable__sortDirection') {
                $this->resetPage();
                $this->reset([
                    'livewireTable__selectedItems',
                    'livewireTable__isAllSelected',
                    'livewireTable__selectedBulkAction'
                ]);
            }

            if (str_starts_with($name, 'livewireTable__selectedItems')) {
                if (count($this->livewireTable__selectedItems) > 0) {
                    if (count($this->livewireTable__selectedItems) === count($this->data())) {
                        $this->livewireTable__isAllSelected = true;
                    } else {
                        $this->livewireTable__isAllSelected = false;
                    }
                } else {
                    $this->livewireTable__selectedBulkAction = '';
                }

                $this->livewireTable__selectedItems = collect($this->livewireTable__selectedItems)->map(function ($item) {
                    if (filter_var($item, FILTER_VALIDATE_INT) !== false) {
                        return (int) $item;
                    }

                    return $item;
                })->toArray();
            }

            if ($name === 'livewireTable__isAllSelected') {
                if ($value) {
                    $selectedItems = [];
                    foreach ($this->data() as $index => $item) {
                        $selectedItems[] = ($this->bulkActionCheckBoxFiller())($item, $index);
                    }

                    $this->livewireTable__selectedItems = $selectedItems;
                } else {
                    $this->livewireTable__selectedItems = [];
                }
            }
        }
    }

    public function sort(string $sortBy): void
    {
        if ($this->livewireTable__sortBy === $sortBy) {
            if ($this->livewireTable__sortDirection === 'asc') {
                $this->livewireTable__sortDirection = 'desc';
            } else {
                $this->livewireTable__sortBy = '';
                $this->livewireTable__sortDirection = '';
            }
        } else {
            $this->livewireTable__sortBy = $sortBy;
            $this->livewireTable__sortDirection = 'asc';
        }
    }

    protected function getSortBy(): string
    {
        return $this->livewireTable__sortBy;
    }

    protected function getSortDirection(): string
    {
        return $this->livewireTable__sortDirection;
    }

    protected function getSearchKeyword(): string
    {
        return $this->livewireTable__search;
    }

    public function render()
    {
        $viewData['livewireTable__columns'] = $this->columns();
        $viewData['livewireTable__data'] = $this->data();
        $viewData['livewireTable__isPaginated'] = $viewData['livewireTable__data'] instanceof LengthAwarePaginator;
        $viewData['livewireTable__noDataMessage'] = $this->noDataMessage();
        $viewData['livewireTable__withSearching'] = $this->withSearching();
        $viewData['livewireTable__searchInputPlaceholder'] = $this->searchInputPlaceholder();
        $viewData['livewireTable__withBulkAction'] = $this->withBulkAction();
        $viewData['livewireTable__bulkActions'] = $this->bulkActions();
        $viewData['livewireTable__bulkActionCheckBoxFiller'] = $this->bulkActionCheckBoxFiller();
        $viewData['livewireTable__bulkActionButtonLabel'] = $this->bulkActionButtonLabel();
        $viewData['livewireTable__bulkActionOptionsLabel'] = $this->bulkActionOptionsLabel();
        $viewData['livewireTable__selectedBulkActionItem'] = $this->getSelectedBulkAction();

        return view('livewire-table::' . config('livewire-table.theme'), $viewData);
    }
}
