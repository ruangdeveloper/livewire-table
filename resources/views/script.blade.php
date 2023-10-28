<div>
    @if ($LTwithBulkAction)
        <script>
            document
                .addEventListener('livewire:initialized', () => {
                    const bulkActionOptions = document
                        .querySelectorAll('#LTselectedBulkAction__{{ $this->getId() }} option')
                    const bulkActionButton = document
                        .getElementById('LTbulkActionButton__{{ $this->getId() }}')
                    const bulkActionConfirmationMessages = [];

                    if (bulkActionOptions.length > 0) {
                        bulkActionOptions.forEach((bulkActionOption) => {
                            bulkActionConfirmationMessages.push({
                                value: bulkActionOption.value,
                                message: bulkActionOption.dataset.confirmationMessage
                            })
                        })
                    }
                    document
                        .getElementById('LTselectedBulkAction__{{ $this->getId() }}')
                        .addEventListener('change', (evt) => {
                            bulkActionButton.setAttribute('wire:confirm', bulkActionConfirmationMessages.find((
                                bulkActionConfirmationMessage) => {
                                return bulkActionConfirmationMessage.value === evt.target.value
                            }).message)
                        })
                })
        </script>
    @endif
</div>
