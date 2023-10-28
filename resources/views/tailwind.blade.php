<div>
    @if ($LTwithSearching || $LTwithBulkAction || $LTwithFilter || $LTwithExport)
        <div class="mb-3 flex flex-wrap gap-3 items-center">
            @if ($LTwithSearching)
                <div class="flex-auto">
                    @if ($LTsearchLabel)
                        <label for="LTsearch"
                            class="block mb-2 text-sm font-medium text-gray-900">{{ $LTsearchLabel }}</label>
                    @endif
                    <input wire:model.live.debounce.500ms="LTsearch" type="search" id="LTsearch__{{ $this->getId() }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                        type="search" placeholder="{{ $LTsearchInputPlaceholder }}" required>
                </div>
            @endif
            @if ($LTwithFilter)
                <div class="inline-flex flex-wrap gap-3 items-center flex-auto">
                    @foreach ($LTfilters as $LTfilterIndex => $LTfilterItem)
                        <div class="flex-auto">
                            @if ($LTfilterItem->getLabel())
                                <label for="{{ $LTfilterItem->getName() }}__{{ $LTfilterIndex }}__{{ $this->getId() }}"
                                    class="block mb-2 text-sm font-medium text-gray-900">{{ $LTfilterItem->getLabel() }}</label>
                            @endif
                            <select wire:model.live="LTfilterData.{{ $LTfilterItem->getName() }}"
                                id="{{ $LTfilterItem->getName() }}__{{ $LTfilterIndex }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
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
                <div class="flex-auto">
                    @if ($LTbulkActionLabel)
                        <div>
                            <label for="LTbulkAction"
                                class="block mb-2 text-sm font-medium text-gray-900">{{ $LTbulkActionLabel }}</label>
                        </div>
                    @endif
                    <div class="flex gap-2 items-center flex-auto">
                        <div class="flex-auto">
                            <select
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                                wire:model="LTselectedBulkAction">
                                <option value="">{{ $LTbulkActionOptionsLabel }}</option>
                                @foreach ($LTbulkActions as $LTbulkAction)
                                    @if ($LTbulkAction->isHidden())
                                        @continue
                                    @else
                                        <option value="{{ $LTbulkAction->getName() }}">
                                            {{ $LTbulkAction->getLabel() }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button wire:click="executeBulkAction"
                                wire:confirm="{{ $LTselectedBulkAction ? $LTselectedBulkActionItem->getConfirmationMessage() : $LTbulkActionOptionsLabel }}"
                                class="px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600">{{ $LTbulkActionButtonLabel }}</button>
                        </div>
                        @if ($LTselectedBulkAction)
                        @endif
                    </div>
                </div>
            @endif
            @if ($LTwithExport)
                <div class="flex-auto text-end">
                    @if ($LTexportLabel)
                        <div>
                            <label for="LTexport"
                                class="block mb-2 text-sm font-medium text-gray-900">{{ $LTexportLabel }}</label>
                        </div>
                    @endif
                    @foreach ($LTexporters as $LTexporterIndex => $LTexporterItem)
                        <button wire:click="handleExport('{{ $LTexporterItem->getName() }}')"
                            class="px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600">{{ $LTexporterItem->getLabel() }}</button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
    @if ($LTwithColumnSelection)
        <div class="flex flex-wrap gap-2 mb-2">
            @foreach ($LTunselectedColumns as $LTunselectedColumnIndex => $LTunselectedColumn)
                <span role="button" wire:click="selectColumn('{{ $LTunselectedColumn->getName() }}')"
                    title="Click to add {{ $LTunselectedColumn->getLabel() }}"
                    class="bg-gray-200 text-gray-800 px-4 py-1">
                    {{ $LTunselectedColumn->getLabel() }}
                </span>
            @endforeach
        </div>
    @endif
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 bg-gray-100">
                <tr>
                    @if ($LTwithBulkAction)
                        <th class="px-3 py-2 border" style="cursor: pointer; width:40px;" title="Select all"
                            scope="col">
                            <div class="flex justify-start items-center">
                                <input wire:model.live="LTisAllSelected" wire:loading.attr="disabled"
                                    id="bulk-action-check-all__{{ $this->getId() }}" type="checkbox" value="true"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
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
                                    class="px-3 py-2 border whitespace-nowrap" scope="col">
                                    <div class="flex items-center justify-between">
                                        <div role="button" wire:click="sort('{{ $LTcolumn->getName() }}')"
                                            title="{{ $LTcolumnSortTitle }}">
                                            {{ $LTcolumn->getLabel() }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div title="{{ $LTcolumnSortTitle }}" role="button"
                                                wire:click="sort('{{ $LTcolumn->getName() }}')">
                                                @if ($LTsortBy !== $LTcolumn->getName())
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                    </svg>
                                                @elseif($LTsortDirection === 'asc')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M8.25 13.5L12 9.75 15.75 13.5" />
                                                    </svg>
                                                @elseif($LTsortDirection === 'desc')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
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
                                                        class="w-5 h-5">
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
                                    class="px-3 py-2 border whitespace-nowrap" scope="col">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            {{ $LTcolumn->getLabel() }}
                                        </div>
                                        @if ($LTwithColumnSelection)
                                            <div role="button" title="Click to remove {{ $LTcolumn->getLabel() }}"
                                                wire:click="removeColumn('{{ $LTcolumn->getName() }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-5 h-5">
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
                    <tr class="bg-white">
                        @if ($LTwithBulkAction)
                            <td class="px-3 py-2 border">
                                <div class="flex justify-start items-center">
                                    <input wire:model.live="LTselectedItems" wire:loading.attr="disabled"
                                        wire:key="bulk-action-check-{{ $LTdataIndex }}"
                                        id="bulk-action-check-{{ $LTdataIndex }}__{{ $this->getId() }}"
                                        type="checkbox"
                                        value="{{ call_user_func($LTbulkActionCheckBoxFiller, $LTdataItem, $LTdataIndex) }}"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                </div>
                            </td>
                        @endif
                        @foreach ($LTcolumns as $LTcolumnIndex => $LTcolumn)
                            @if ($LTcolumn->isHidden())
                                @continue
                            @else
                                <td class="px-3 py-2 border">
                                    {!! call_user_func($LTcolumn->getRenderer(), $LTdataItem, $LTdataIndex) !!}
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @empty
                    <tr class="bg-white">
                        <td class="px-3 py-2 border text-center"
                            colspan="{{ sizeof($LTcolumns) + ($LTwithBulkAction ? 1 : 0) }}">
                            {{ $LTnoDataMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($LTisPaginated)
        <div class="overflow-x-auto">
            <div class="mt-6 flex justify-end items-center gap-2">
                <div class="flex-1">
                    {{ $LTdata->links() }}
                </div>
                @if ($LTwithPagination && $LTdata->hasPages())
                    <div>
                        <select wire:model.live="LTperPage"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
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
    <div wire:loading.delay class="w-full mt-3 bg-gray-100">
        <div class="border p-2 rounded-md">
            Loading...
        </div>
    </div>
</div>
