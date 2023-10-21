<div>
    @if ($LTwithSearching || $LTwithBulkAction || $LTwithFilter || $LTwithExport)
        <div>
            @if ($LTwithSearching)
                <div>
                    @if ($LTsearchLabel)
                        <label for="LTsearch">{{ $LTsearchLabel }}</label>
                    @endif
                    <input wire:model.live.debounce.500ms="LTsearch" type="search" id="LTsearch" type="search"
                        placeholder="{{ $LTsearchInputPlaceholder }}" required>
                </div>
            @endif
            @if ($LTwithFilter)
                <div>
                    @foreach ($LTfilters as $LTfilterIndex => $LTfilterItem)
                        <div>
                            @if ($LTfilterItem->getLabel())
                                <label
                                    for="{{ $LTfilterItem->getName() }}__{{ $LTfilterIndex }}">{{ $LTfilterItem->getLabel() }}</label>
                            @endif
                            <select wire:model.live="LTfilterData.{{ $LTfilterItem->getName() }}"
                                id="{{ $LTfilterItem->getName() }}__{{ $LTfilterIndex }}">
                                @foreach ($LTfilterItem->getFilterOptions() as $LTfilterOptionIndex => $LTfilterOptionItem)
                                    <option id="{{ $LTfilterOptionItem->getValue() }}__{{ $LTfilterOptionIndex }}"
                                        value="{{ $LTfilterOptionItem->getValue() }}">
                                        {{ $LTfilterOptionItem->getLabel() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            @endif
            @if ($LTwithBulkAction && count($LTselectedItems) > 0)
                <div>
                    @if ($LTbulkActionLabel)
                        <div>
                            <label for="LTbulkAction">{{ $LTbulkActionLabel }}</label>
                        </div>
                    @endif
                    <div>
                        <div>
                            <select wire:model.live="LTselectedBulkAction">
                                <option value="">{{ $LTbulkActionOptionsLabel }}</option>
                                @foreach ($LTbulkActions as $LTbulkAction)
                                    <option value="{{ $LTbulkAction->getName() }}">
                                        {{ $LTbulkAction->getLabel() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button wire:click="executeBulkAction"
                                wire:confirm="{{ $LTselectedBulkAction ? $LTselectedBulkActionItem->getConfirmationMessage() : $LTbulkActionOptionsLabel }}">{{ $LTbulkActionButtonLabel }}</button>
                        </div>
                    </div>
                </div>
            @endif
            @if ($LTwithExport)
                <div>
                    @if ($LTexportLabel)
                        <div>
                            <label for="LTexport">{{ $LTexportLabel }}</label>
                        </div>
                    @endif
                    @foreach ($LTexporters as $LTexporterIndex => $LTexporterItem)
                        <button wire:click="handleExport('{{ $LTexporterItem->getName() }}')">
                            {{ $LTexporterItem->getLabel() }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
    @if ($LTwithColumnSelection)
        <div>
            @foreach ($LTunselectedColumns as $LTunselectedColumnIndex => $LTunselectedColumn)
                <span role="button" wire:click="selectColumn('{{ $LTunselectedColumn->getName() }}')"
                    title="Click to add {{ $LTunselectedColumn->getLabel() }}">
                    {{ $LTunselectedColumn->getLabel() }}
                </span>
            @endforeach
        </div>
    @endif
    <div wire:loading.delay>
        <div>
            Loading...
        </div>
    </div>
    <div>
        <table>
            <thead>
                <tr>
                    @if ($LTwithBulkAction)
                        <th title="Select all" scope="col">
                            <div>
                                <input wire:model.live="LTisAllSelected" id="bulk-action-check-all" type="checkbox"
                                    value="true">
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
                                <th id="{{ $LTcolumn->getName() . '__' . $LTcolumnIndex }}" scope="col">
                                    <div>
                                        <div role="button" wire:click="sort('{{ $LTcolumn->getName() }}')"
                                            title="{{ $LTcolumnSortTitle }}">
                                            {{ $LTcolumn->getLabel() }}
                                        </div>
                                        <div>
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
                                <th id="{{ $LTcolumn->getName() . '__' . $LTcolumnIndex }}" scope="col">
                                    <div>
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
                    <tr>
                        @if ($LTwithBulkAction)
                            <td>
                                <div>
                                    <input wire:model.live="LTselectedItems"
                                        wire:key="bulk-action-check-{{ $LTdataIndex }}"
                                        id="bulk-action-check-{{ $LTdataIndex }}" type="checkbox"
                                        value="{{ call_user_func($LTbulkActionCheckBoxFiller, $LTdataItem, $LTdataIndex) }}">
                                </div>
                            </td>
                        @endif
                        @foreach ($LTcolumns as $LTcolumnIndex => $LTcolumn)
                            @if ($LTcolumn->isHidden())
                                @continue
                            @else
                                <td>
                                    {{ call_user_func($LTcolumn->getRenderer(), $LTdataItem, $LTdataIndex) }}
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ sizeof($LTcolumns) + ($LTwithBulkAction ? 1 : 0) }}">
                            {{ $LTnoDataMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($LTisPaginated)
        <div>
            <div>
                <div>
                    {{ $LTdata->links() }}
                </div>
                @if ($LTwithPagination && $LTdata->hasPages())
                    <div>
                        <select wire:model.live="LTperPage">
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
</div>
