<div>
    @if ($LTwithSearching || $LTwithBulkAction || $LTwithFilter || $LTwithExport)
        <div class="mb-3 d-flex flex-wrap gap-3 align-items-center">
            @if ($LTwithSearching)
                <div class="flex-fill">
                    @if ($LTsearchLabel)
                        <label for="LTsearch" class="form-label">{{ $LTsearchLabel }}</label>
                    @endif
                    <input wire:model.live.debounce.500ms="LTsearch" type="search" id="LTsearch__{{ $this->getId() }}"
                        class="form-control bg-light" type="search" placeholder="{{ $LTsearchInputPlaceholder }}"
                        required>
                </div>
            @endif
            @if ($LTwithFilter)
                <div class="d-inline-flex flex-wrap gap-3 align-items-center flex-fill">
                    @foreach ($LTfilters as $LTfilterIndex => $LTfilterItem)
                        <div class="flex-fill">
                            @if ($LTfilterItem->getLabel())
                                <label for="{{ $LTfilterItem->getName() }}__{{ $LTfilterIndex }}"
                                    class="form-label">{{ $LTfilterItem->getLabel() }}</label>
                            @endif
                            <select wire:model.live="LTfilterData.{{ $LTfilterItem->getName() }}"
                                id="{{ $LTfilterItem->getName() }}__{{ $LTfilterIndex }}__{{ $this->getId() }}"
                                class="form-select bg-light">
                                @foreach ($LTfilterItem->getFilterOptions() as $LTfilterOptionIndex => $LTfilterOptionItem)
                                    <option
                                        id="{{ $LTfilterOptionItem->getValue() }}__{{ $LTfilterOptionIndex }}__{{ $this->getId() }}"
                                        value="{{ $LTfilterOptionItem->getValue() }}">
                                        {{ $LTfilterOptionItem->getLabel() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            @endif
            @if ($LTwithBulkAction)
                <div class="flex-fill">
                    @if ($LTbulkActionLabel)
                        <div>
                            <label for="LTbulkAction" class="form-label">{{ $LTbulkActionLabel }}</label>
                        </div>
                    @endif
                    <div class="d-flex gap-2 align-items-center flex-fill">
                        <div class="flex-fill">
                            <select class="form-select bg-light" wire:model="LTselectedBulkAction"
                                id="LTselectedBulkAction__{{ $this->getId() }}">
                                <option value="" data-confirmation-message="{{ $LTbulkActionOptionsLabel }}">
                                    {{ $LTbulkActionOptionsLabel }}</option>
                                @foreach ($LTbulkActions as $LTbulkAction)
                                    @if ($LTbulkAction->isHidden())
                                        @continue
                                    @else
                                        <option value="{{ $LTbulkAction->getName() }}"
                                            data-confirmation-message="{{ $LTbulkAction->getConfirmationMessage() }}">
                                            {{ $LTbulkAction->getLabel() }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button wire:click="executeBulkAction" wire:confirm="{{ $LTbulkActionOptionsLabel }}"
                                id="LTbulkActionButton__{{ $this->getId() }}"
                                class="btn btn-primary">{{ $LTbulkActionButtonLabel }}</button>
                        </div>
                    </div>
                </div>
            @endif
            @if ($LTwithExport)
                <div class="flex-fill text-end">
                    @if ($LTexportLabel)
                        <div>
                            <label for="LTexport" class="form-label">{{ $LTexportLabel }}</label>
                        </div>
                    @endif
                    @foreach ($LTexporters as $LTexporterIndex => $LTexporterItem)
                        <button wire:click="handleExport('{{ $LTexporterItem->getName() }}')"
                            class="btn btn-primary">{{ $LTexporterItem->getLabel() }}</button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
    @if ($LTwithColumnSelection)
        <div class="d-flex flex-wrap gap-2 mb-2">
            @foreach ($LTunselectedColumns as $LTunselectedColumnIndex => $LTunselectedColumn)
                <span role="button" wire:click="selectColumn('{{ $LTunselectedColumn->getName() }}')"
                    title="Click to add {{ $LTunselectedColumn->getLabel() }}" class="badge bg-secondary rounded-0">
                    {{ $LTunselectedColumn->getLabel() }}
                </span>
            @endforeach
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered table-striped shadow-sm">
            <thead class="table-light">
                <tr>
                    @if ($LTwithBulkAction)
                        <th style="cursor: pointer; width:40px;" title="Select all" scope="col">
                            <div class="d-flex justify-content-start align-items-center">
                                <input wire:model.live="LTisAllSelected" wire:loading.attr="disabled"
                                    id="bulk-action-check-all__{{ $this->getId() }}" type="checkbox" value="true"
                                    class="form-check">
                            </div>
                        </th>
                    @endif
                    @foreach ($LTcolumns as $LTcolumnIndex => $LTcolumn)
                        @if ($LTcolumn->isHidden())
                            @continue
                        @else
                            @if ($LTcolumn->isSortable())
                                @php
                                    $LTcolumnSortTitle = '';
                                    if ($LTsortBy !== $LTcolumn->getName()) {
                                        $LTcolumnSortTitle = 'Click to sort ascending';
                                    } elseif ($LTsortDirection === 'asc') {
                                        $LTcolumnSortTitle = 'Click to sort descending';
                                    } elseif ($LTsortDirection === 'desc') {
                                        $LTcolumnSortTitle = 'Click to clear sorting';
                                    }
                                @endphp
                                <th id="{{ $LTcolumn->getName() . '__' . $LTcolumnIndex }}__{{ $this->getId() }}"
                                    class="text-nowrap" scope="col">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div role="button" wire:click="sort('{{ $LTcolumn->getName() }}')"
                                            title="{{ $LTcolumnSortTitle }}">
                                            {{ $LTcolumn->getLabel() }}
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            <div title="{{ $LTcolumnSortTitle }}" role="button"
                                                wire:click="sort('{{ $LTcolumn->getName() }}')">
                                                @if ($LTsortBy !== $LTcolumn->getName())
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        style="width: 25px; height:25px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                    </svg>
                                                @elseif($LTsortDirection === 'asc')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        style="width: 25px; height:25px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M8.25 13.5L12 9.75 15.75 13.5" />
                                                    </svg>
                                                @elseif($LTsortDirection === 'desc')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        style="width: 25px; height:25px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M8.25 10.5L12 14.25 15.75 10.5" />
                                                    </svg>
                                                @endif
                                            </div>
                                            @if ($LTwithColumnSelection)
                                                <div role="button" title="Click to remove {{ $LTcolumn->getLabel() }}"
                                                    wire:click="removeColumn('{{ $LTcolumn->getName() }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        style="width: 25px; height:25px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </th>
                            @else
                                <th id="{{ $LTcolumn->getName() . '__' . $LTcolumnIndex }}__{{ $this->getId() }}"
                                    class="text-nowrap" scope="col">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            {{ $LTcolumn->getLabel() }}
                                        </div>
                                        @if ($LTwithColumnSelection)
                                            <div role="button" title="Click to remove {{ $LTcolumn->getLabel() }}"
                                                wire:click="removeColumn('{{ $LTcolumn->getName() }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    style="width: 25px; height:25px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </th>
                            @endif
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($LTdata as $LTdataIndex => $LTdataItem)
                    <tr class="bg-light">
                        @if ($LTwithBulkAction)
                            <td>
                                <div class="d-flex justify-content-start align-items-center">
                                    <input wire:model.live="LTselectedItems" wire:loading.attr="disabled"
                                        wire:key="bulk-action-check-{{ $LTdataIndex }}"
                                        id="bulk-action-check-{{ $LTdataIndex }}__{{ $this->getId() }}"
                                        type="checkbox"
                                        value="{{ call_user_func($LTbulkActionCheckBoxFiller, $LTdataItem, $LTdataIndex) }}"
                                        class="form-check">
                                </div>
                            </td>
                        @endif
                        @foreach ($LTcolumns as $LTcolumnIndex => $LTcolumn)
                            @if ($LTcolumn->isHidden())
                                @continue
                            @else
                                <td>
                                    {!! call_user_func($LTcolumn->getRenderer(), $LTdataItem, $LTdataIndex) !!}
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ sizeof($LTcolumns) + ($LTwithBulkAction ? 1 : 0) }}" class="text-center">
                            {{ $LTnoDataMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($LTisPaginated)
        <div style="overflow-x: auto;">
            <div class="mt-3 d-flex justify-content-end align-items-center gap-2">
                <div>
                    {{ $LTdata->links() }}
                </div>
                @if ($LTwithPagination && $LTdata->hasPages())
                    <div>
                        <select wire:model.live="LTperPage" class="form-select mb-3">
                            @foreach ($LTperPageOptions as $LTperPageOption)
                                <option value="{{ $LTperPageOption }}">
                                    {{ $LTperPageOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>
    @endif
    <div wire:loading.delay class="w-100 mt-3 bg-white">
        <div class="border p-2 rounded">
            Loading...
        </div>
    </div>
    @include('livewire-table::script')
</div>
