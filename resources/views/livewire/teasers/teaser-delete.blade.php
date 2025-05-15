<div>
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900">Bestätigen Sie die Löschung</h3>
                <p class="mt-2 text-gray-500">Sind Sie sicher, dass Sie diesen Teaser löschen möchten? Diese Aktion ist irreversibel.</p>
                <div class="mt-4 flex justify-end space-x-3">
                    <button wire:click="cancelDelete" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">
                        Abbrechen
                    </button>
                    <button wire:click="deleteTeaser" class="px-4 py-2 bg-red-600 text-white rounded-md">
                        Löschen
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
