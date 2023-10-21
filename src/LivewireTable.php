<?php

namespace RuangDeveloper\LivewireTable;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use RuangDeveloper\LivewireTable\Traits\WithSorting;
use RuangDeveloper\LivewireTable\Traits\WithSearching;
use RuangDeveloper\LivewireTable\Traits\WithBulkAction;
use RuangDeveloper\LivewireTable\Traits\WithFilter;
use RuangDeveloper\LivewireTable\Traits\WithColumnSelection;
use RuangDeveloper\LivewireTable\Traits\WithPagination;
use RuangDeveloper\LivewireTable\Traits\WithExport;

abstract class LivewireTable extends Component
{
    use WithSorting;

    const THEME_BOOTSTRAP = 'bootstrap';
    const THEME_TAILWIND = 'tailwind';
    const THEME_CUSTOM = 'custom';

    public function getTableTheme(): string
    {
        return self::THEME_BOOTSTRAP;
    }

    public function getColumns(): array
    {
        return [];
    }

    public function getData(): Collection|LengthAwarePaginator|Paginator|array
    {
        return [];
    }

    public function getNoDataMessage(): string
    {
        return 'No data available.';
    }

    private function withSearching(): bool
    {
        return in_array(WithSearching::class, class_uses($this));
    }

    private function withBulkAction(): bool
    {
        return in_array(WithBulkAction::class, class_uses($this));
    }

    private function withFilter(): bool
    {
        return in_array(WithFilter::class, class_uses($this));
    }

    private function withColumnSelection(): bool
    {
        return in_array(WithColumnSelection::class, class_uses($this));
    }

    private function withPagination(): bool
    {
        return in_array(WithPagination::class, class_uses($this));
    }

    private function withExport(): bool
    {
        return in_array(WithExport::class, class_uses($this));
    }

    public function render()
    {
        $viewData['LTcolumns'] = $this->getColumns();
        $viewData['LTwithColumnSelection'] = $this->withColumnSelection();
        if ($viewData['LTwithColumnSelection']) {
            $viewData['LTcolumns'] = $this->getSelectedColumns();
            $viewData['LTunselectedColumns'] = $this->getUnselectedColumns();
        }
        $viewData['LTdata'] = $this->getData();
        $viewData['LTisPaginated'] = $viewData['LTdata'] instanceof LengthAwarePaginator || $viewData['LTdata'] instanceof Paginator;
        $viewData['LTnoDataMessage'] = $this->getNoDataMessage();
        $viewData['LTwithSearching'] = $this->withSearching();
        if ($viewData['LTwithSearching']) {
            $viewData['LTsearchInputPlaceholder'] = $this->getSearchInputPlaceholder();
            $viewData['LTsearchLabel'] = $this->getSearchLabel();
        }
        $viewData['LTwithBulkAction'] = $this->withBulkAction();
        if ($viewData['LTwithBulkAction']) {
            $viewData['LTbulkActions'] = $this->getBulkActions();
            $viewData['LTbulkActionLabel'] = $this->getBulkActionLabel();
            $viewData['LTbulkActionCheckBoxFiller'] = $this->getBulkActionCheckBoxFiller();
            $viewData['LTbulkActionButtonLabel'] = $this->getBulkActionButtonLabel();
            $viewData['LTbulkActionOptionsLabel'] = $this->getBulkActionOptionsLabel();
            $viewData['LTselectedBulkActionItem'] = $this->getSelectedBulkAction();
        }
        $viewData['LTwithFilter'] = $this->withFilter();
        if ($viewData['LTwithFilter']) {
            $viewData['LTfilters'] = $this->getFilters();
        }
        $viewData['LTwithPagination'] = $this->withPagination();
        if ($viewData['LTwithPagination']) {
            $viewData['LTperPageOptions'] = $this->getPerPageOptions();
        }
        $viewData['LTwithExport'] = $this->withExport();
        if ($viewData['LTwithExport']) {
            $viewData['LTexportLabel'] = $this->getExportLabel();
            $viewData['LTexporters'] = $this->getExporters();
        }

        return view('livewire-table::' . $this->getTableTheme(), $viewData);
    }
}
