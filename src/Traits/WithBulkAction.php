<?php

namespace RuangDeveloper\LivewireTable\Traits;

use Closure;
use RuangDeveloper\LivewireTable\Components\BulkAction;

trait WithBulkAction
{
    public array $LTselectedItems = [];

    public bool $LTisAllSelected = false;

    public string $LTselectedBulkAction = '';

    public function getBulkActionLabel(): ?string
    {
        return 'Bulk Action';
    }

    public function getBulkActions(): array
    {
        return [];
    }

    public function getBulkActionCheckBoxFiller(): Closure
    {
        return function ($item, $index) {
            return $index;
        };
    }

    public function getBulkActionButtonLabel(): string
    {
        return 'Apply';
    }

    public function getBulkActionOptionsLabel(): string
    {
        return 'Choose an action...';
    }

    public function getSelectedItems(): array
    {
        return $this->LTselectedItems;
    }

    public function executeBulkAction()
    {
        $bulkActions = collect($this->getBulkActions())->map(function (BulkAction $bulkAction) {
            return [
                'name' => $bulkAction->getName(),
                'handler' => $bulkAction->getHandler(),
                'isHidden' => $bulkAction->isHidden(),
            ];
        })->toArray();

        if ($this->LTselectedBulkAction === '') {
            return;
        }

        $executionResult = null;

        foreach ($bulkActions as $bulkAction) {
            if (
                $bulkAction['name'] === $this->LTselectedBulkAction &&
                !$bulkAction['isHidden']
            ) {
                $executionResult = ($bulkAction['handler'])($this->LTselectedItems);
                $this->reset(['LTselectedItems', 'LTisAllSelected', 'LTselectedBulkAction']);
                break;
            }
        }

        return $executionResult;
    }

    public function getSelectedBulkAction(): ?BulkAction
    {
        $bulkActions = $this->getBulkActions();

        foreach ($bulkActions as $bulkAction) {
            if ($bulkAction->getName() === $this->LTselectedBulkAction) {
                return $bulkAction;
            }
        }

        return null;
    }

    public function updatedWithBulkAction($name, $value)
    {
        if (str_starts_with($name, 'LTselectedItems')) {
            if (count($this->LTselectedItems) > 0) {
                if (count($this->LTselectedItems) === count($this->getData())) {
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

        if (str_starts_with($name, 'LTisAllSelected')) {
            if ($value) {
                $selectedItems = [];
                foreach ($this->getData() as $index => $item) {
                    $selectedItems[] = ($this->getBulkActionCheckBoxFiller())($item, $index);
                }

                $this->LTselectedItems = $selectedItems;
            } else {
                $this->LTselectedItems = [];
            }
        }
    }
}
