<?php

namespace RuangDeveloper\LivewireTable\Traits;

use Closure;
use RuangDeveloper\LivewireTable\Components\BulkAction;

trait WithBulkAction
{
    public array $LTselectedItems = [];

    public bool $LTisAllSelected = false;

    public string $LTselectedBulkAction = '';

    public function bulkActionLabel(): string
    {
        return 'Bulk Action';
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
        return $this->LTselectedItems;
    }

    public function executeBulkAction()
    {
        $bulkActions = collect($this->bulkActions())->map(function (BulkAction $bulkAction) {
            return [
                'name' => $bulkAction->getName(),
                'handler' => $bulkAction->getHandler(),
            ];
        })->toArray();

        if ($this->LTselectedBulkAction === '') {
            return;
        }

        foreach ($bulkActions as $bulkAction) {
            if ($bulkAction['name'] === $this->LTselectedBulkAction) {
                ($bulkAction['handler'])($this->LTselectedItems);
                $this->reset(['LTselectedItems', 'LTisAllSelected', 'LTselectedBulkAction']);
            }
        }
    }

    public function getSelectedBulkAction(): ?BulkAction
    {
        $bulkActions = $this->bulkActions();

        foreach ($bulkActions as $bulkAction) {
            if ($bulkAction->getName() === $this->LTselectedBulkAction) {
                return $bulkAction;
            }
        }

        return null;
    }

    public function updatedLTSelectedItems()
    {
        if (count($this->LTselectedItems) > 0) {
            if (count($this->LTselectedItems) === count($this->data())) {
                $this->LTisAllSelected = true;
            } else {
                $this->LTisAllSelected = false;
            }
        } else {
            $this->LTselectedBulkAction = '';
        }

        $this->LTselectedItems = collect($this->LTselectedItems)->map(function ($item) {
            if (filter_var($item, FILTER_VALIDATE_INT) !== false) {
                return (int) $item;
            }

            return $item;
        })->toArray();
    }

    public function updatedLTIsAllSelected($value)
    {
        if ($value) {
            $selectedItems = [];
            foreach ($this->data() as $index => $item) {
                $selectedItems[] = ($this->bulkActionCheckBoxFiller())($item, $index);
            }

            $this->LTselectedItems = $selectedItems;
        } else {
            $this->LTselectedItems = [];
        }
    }
}
