<div>
    @if ($livewireTable__withSearching || $livewireTable__withBulkAction)
        <div class="mb-3 d-inline-flex gap-2 align-items-center">
            @if ($livewireTable__withSearching)
                <div>
                    <input wire:model.live.debounce.500ms="livewireTable__search" type="search" id="livewireTable__search"
                        class="form-control" type="search" placeholder="{{ $livewireTable__searchInputPlaceholder }}"
                        required>
                </div>
            @endif
            @if ($livewireTable__withBulkAction && count($livewireTable__selectedItems) > 0)
                <div>
                    <select class="form-select" wire:model.live="livewireTable__selectedBulkAction">
                        <option value="">{{ $livewireTable__bulkActionOptionsLabel }}</option>
                        @foreach ($livewireTable__bulkActions as $livewireTable__bulkAction)
                            <option value="{{ $livewireTable__bulkAction->getName() }}">
                                {{ $livewireTable__bulkAction->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($livewireTable__selectedBulkAction)
                    <div>
                        <button wire:click="executeBulkAction"
                            wire:confirm="{{ $livewireTable__selectedBulkActionItem->getConfirmationMessage() }}"
                            class="btn btn-primary">{{ $livewireTable__bulkActionButtonLabel }}</button>
                    </div>
                @endif
            @endif
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    @if ($livewireTable__withBulkAction)
                        <th style="cursor: pointer;" title="Select all" scope="col">
                            <div class="d-flex justify-content-center align-items-center">
                                <input wire:model.live="livewireTable__isAllSelected" id="bulk-action-check-all"
                                    type="checkbox" value="true" class="form-check">
                            </div>
                        </th>
                    @endif
                    @foreach ($livewireTable__columns as $livewireTable__columnIndex => $livewireTable__column)
                        @if ($livewireTable__column->isHidden())
                            @continue
                        @else
                            @if ($livewireTable__column->isSortable())
                                @php
                                    $livewireTable__columnSortTitle = '';
                                    if ($livewireTable__sortBy !== $livewireTable__column->getName()) {
                                        $livewireTable__columnSortTitle = 'Click to sort ascending';
                                    } elseif ($livewireTable__sortDirection === 'asc') {
                                        $livewireTable__columnSortTitle = 'Click to sort descending';
                                    } elseif ($livewireTable__sortDirection === 'desc') {
                                        $livewireTable__columnSortTitle = 'Click to clear sorting';
                                    }
                                @endphp
                                <th title="{{ $livewireTable__columnSortTitle }}" style="cursor: pointer;"
                                    role="button"
                                    id="{{ $livewireTable__column->getName() . '__' . $livewireTable__columnIndex }}"
                                    class="text-nowrap" scope="col"
                                    wire:click="sort('{{ $livewireTable__column->getName() }}')">
                                    <div class="d-flex align-items-center justify-content-between">
                                        {{ $livewireTable__column->getLabel() }}
                                        @if ($livewireTable__sortBy !== $livewireTable__column->getName())
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                style="width: 25px; height:25px;">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        @elseif($livewireTable__sortDirection === 'asc')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                style="width: 25px; height:25px;">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.25 13.5L12 9.75 15.75 13.5" />
                                            </svg>
                                        @elseif($livewireTable__sortDirection === 'desc')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                style="width: 25px; height:25px;">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.25 10.5L12 14.25 15.75 10.5" />
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                            @else
                                <th id="{{ $livewireTable__column->getName() . '__' . $livewireTable__columnIndex }}"
                                    class="text-nowrap" scope="col">
                                    {{ $livewireTable__column->getLabel() }}
                                </th>
                            @endif
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($livewireTable__data as $livewireTable__dataIndex => $livewireTable__dataItem)
                    <tr>
                        @if ($livewireTable__withBulkAction)
                            <td>
                                <div class="d-flex justify-content-center align-items-center">
                                    <input wire:model.live="livewireTable__selectedItems"
                                        wire:key="bulk-action-check-{{ $livewireTable__dataIndex }}"
                                        id="bulk-action-check-{{ $livewireTable__dataIndex }}" type="checkbox"
                                        value="{{ call_user_func($livewireTable__bulkActionCheckBoxFiller, $livewireTable__dataItem, $livewireTable__dataIndex) }}"
                                        class="form-check">
                                </div>
                            </td>
                        @endif
                        @foreach ($livewireTable__columns as $livewireTable__columnIndex => $livewireTable__column)
                            @if ($livewireTable__column->isHidden())
                                @continue
                            @else
                                <td>
                                    {{ call_user_func($livewireTable__column->getRenderer(), $livewireTable__dataItem, $livewireTable__dataIndex) }}
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ sizeof($livewireTable__columns) }}" class="text-center">
                            {{ $livewireTable__noDataMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($livewireTable__isPaginated)
        <div class="mt-3 d-flex justify-content-end">
            {{ $livewireTable__data->links() }}
        </div>
    @endif
</div>
