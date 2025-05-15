
<div>
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-primary-base text-center py-3 px-4">
            <h1 class="text-3xl  text-white font-semibold">Inhalte hochladen</h1>
        </div>

        <form wire:submit="save" enctype="multipart/form-data" class="p-6">
            @csrf

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
                                <div class="mb-2" style="width: 300px; height: 150px; overflow: hidden;">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full h-full object-contain">
                                </div>
                                <button type="button" wire:click="resetImage" class="bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-full px-4 py-2 cursor-pointer flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Bild ändern
                                </button>
                            </div>
                        @elseif (isset($teaser) && $teaser->image_name)
                            <div class="w-full h-full flex flex-col items-center justify-center">
                                <div class="mb-2" style="width: 300px; height: 150px; overflow: hidden;">
                                    <img src="{{ asset('storage/teasers/' . $teaser->id . '/' . $teaser->image_name) }}" alt="Current image" class="w-full h-full object-contain">
                                </div>
                                <button type="button" wire:click="resetImage" class="bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-full px-4 py-2 cursor-pointer flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Bild ändern
                                </button>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center"
                                 x-data="{ isHovering: false }"
                                 x-on:dragover.prevent="isHovering = true"
                                 x-on:dragleave.prevent="isHovering = false"
                                 x-on:drop.prevent="isHovering = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'));"
                                 :class="{ 'bg-green-50': isHovering }">
                                <label for="image-upload" class="bg-primary-100 hover:bg-primary-200 text-primary-600 rounded-full px-4 py-2 mb-3 cursor-pointer flex items-center font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                                    </svg>
                                    Datei hochladen
                                </label>
                                <input type="file" id="image-upload" wire:model.live="image" x-ref="fileInput" class="hidden" accept="image/*">
                                <p class="text-gray-500 text-sm font-bold">oder Drag and Drop</p>
                            </div>
                        @endif
                    </div>
                    @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-center xl:justify-end  mt-6">
                <button type="submit"
                        class="px-6 py-2 bg-primary-base text-white rounded-full w-full md:w-60 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    <span wire:loading.remove>Inhalte ausspielen</span>
                    <span wire:loading>Wird gespeichert...</span>
                </button>
            </div>
        </form>
    </div>

    @if(isset($isLoading) && $isLoading)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-500"></div>
                </div>
                <p class="mt-4 text-center text-gray-700">loading...</p>
            </div>
        </div>
    @endif
</div>
