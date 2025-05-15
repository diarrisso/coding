<div class="mt-15">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-primary-base text-white text-center">
            <h1 class="text-sm md:text-3xl text-white font-bold p-1">Inhalte Bearbeiten</h1>
        </div>
        <form wire:submit="updateTeaser" enctype="multipart/form-data" class="p-6">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <p class="font-medium">Bitte korrigieren Sie die folgenden Fehler :</p>
                    <ul class="mt-1.5 ml-4 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <div>
                        <x-input-with-error name="title" label="Überschrift" />
                    </div>

                    <div>
                        <label for="description" class="block text-gray-500 mb-2">Text</label>
                        <textarea id="description" wire:model.live="description" rows="8"
                                  class="w-full border border-gray-300 rounded-md p-2.5 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                        @error('description')
                        <span class="text-red-500 text-xs mt-1 block">
                        {{ $message }}
                    </span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-gray-500 mb-2">Bild hochladen</label>
                    <div class="border border-gray-300 rounded-md p-4 h-[200px] flex flex-col items-center justify-center text-center relative">
                        @if ($image)
                            <div class="w-full h-full flex flex-col items-center justify-center">
                                <div class="w-full h-full flex items-center justify-center">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="max-h-[180px] max-w-full object-contain">
                                </div>
                                <div class="mt-2">
                                    <button type="button" wire:click="resetImage" class="text-xs bg-blue-500 hover:bg-blue-600 text-white rounded-full px-3 py-1 cursor-pointer flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Ändern
                                    </button>
                                </div>
                            </div>
                        @elseif (isset($teaser) && $teaser->image_name)
                            <div class="w-full h-full flex flex-col items-center justify-center">
                                <div class="w-full h-full flex items-center justify-center">
                                    <img src="{{ asset('storage/teasers/' . $teaser->id . '/' . $teaser->image_name) }}" alt="Current image" class="max-h-[150px] max-w-full object-contain">
                                </div>
                                <div class="mt-2">
                                    <label for="image-replace" class="text-xs bg-blue-500 hover:bg-blue-600 text-white rounded-full px-3 py-1 cursor-pointer flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                                        </svg>
                                        Ersetzen
                                    </label>
                                    <input type="file" id="image-replace" wire:model="image" class="hidden" accept="image/*">
                                </div>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center"
                                 x-data="{ isHovering: false }"
                                 x-on:dragover.prevent="isHovering = true"
                                 x-on:dragleave.prevent="isHovering = false"
                                 x-on:drop.prevent="isHovering = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'));"
                                 :class="{ 'bg-green-50': isHovering }">
                                <label for="image-upload" class="bg-green-100 hover:bg-green-200 text-green-600 rounded-full px-4 py-2 mb-3 cursor-pointer flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                                    </svg>
                                    Datei hochladen
                                </label>
                                <input type="file" id="image-upload" wire:model="image" x-ref="fileInput"
                                       class="hidden" accept="image/*">
                                <p class="text-gray-500 text-sm">oder Drag and Drop</p>
                            </div>
                        @endif
                    </div>
                    @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-center md:justify-end mt-6">
                <button type="submit"
                        class="px-6 py-2 w-full md:w-60 font-semibold text-xl cursor-pointer bg-primary-base text-white rounded-full hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-bg-primary-500 focus:ring-offset-2">
                    <span wire:loading.remove>Inhalte ausspielen</span>
                    <span wire:loading>Wird gespeichert...</span>
                </button>
            </div>
        </form>
    </div>
</div>
