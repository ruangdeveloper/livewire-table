<?php

namespace RuangDeveloper\LivewireTable\Traits;

trait WithFilter
{
    public $LTfilterDataString = '';
    public $LTfilterData = [];

    public function getFilters(): array
    {
        return [];
    }

    public function enableFilterQueryString(): bool
    {
        return false;
    }

    public function updatedWithFilter(string $name, mixed $value): void
    {
        if (str_starts_with($name, 'LTfilterData.')) {
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

            $this->LTfilterDataString = collect($this->LTfilterData)->map(function ($value, $key) {
                return $key . ':' . $value;
            })->implode(',');
        }
    }

    public function mountWithFilter(): void
    {
        if (empty($this->LTfilterDataString)) {

            foreach ($this->getFilters() as $filter) {
                $initialValue = '';
                if (count($filter->getFilterOptions()) > 0) {
                    $initialValue = $filter->getFilterOptions()[0]->getValue();
                }
                $this->LTfilterData[$filter->getName()] = $initialValue;
            }

            $this->LTfilterDataString = collect($this->LTfilterData)->map(function ($value, $key) {
                return $key . ':' . $value;
            })->implode(',');
        } else {
            $this->LTfilterData = collect(explode(',', $this->LTfilterDataString))
                ->mapWithKeys(function ($filter) {
                    [$key, $value] = explode(':', $filter);
                    return [$key => $value];
                })->toArray();
        }
    }

    public function getFilterData($name = null): mixed
    {
        if ($name) {
            return $this->LTfilterData[$name];
        }

        return $this->LTfilterData;
    }

    protected function queryStringWithFilter(): array
    {
        if (!$this->enableFilterQueryString()) return [];

        return [
            'LTfilterDataString' => [
                'as' => 'filters',
                'except' => '',
            ],
        ];
    }
}
