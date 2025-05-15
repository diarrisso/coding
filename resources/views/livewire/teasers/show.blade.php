<div>
    <livewire:teaser-delete />
    <div class=" pt-4 md:pt-12 pb-8 flex justify-center">
        <div class="max-w-[960px] w-full mx-auto md:px-4">
            <div class="relative overflow-hidden md:rounded-2xl aspect-video ">
                <img
                    src="{{ Storage::url($teaser->image_name) }}"
                    alt="Bild"
                    class="w-full md:h-full object-cover bg-gray-100"
                >
                @if (Auth::user()->isAdmin())
                    <div class="absolute top-2 right-2 flex space-x-2">
                        <a href="{{ route('teasers.edit', $teaser) }}"
                           class="p-1.5 bg-zinc-500 bg-opacity-80 hover:bg-opacity-100 hover:bg-zinc-700 text-white rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>

                        <button
                            wire:click="$dispatch('confirmDeleteTeaser', { teaserId: {{ $teaser->id }} })"
                            class="p-1.5 bg-red-600 bg-opacity-80 hover:bg-opacity-100 hover:bg-red-700 text-white rounded-full flex items-center justify-center cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                @endif
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/100 to-transparent text-white">
                    <h3 class="md:text-5xl text-white sm:text-4xl xs:text-3xl font-bold p-5 md:p-5 sm:p-4 xs:p-3">{{ $teaser->title }}</h3>
                </div>
            </div>

            <div class="mt-6 text-lg  text-black dark:text-white mb-11 px-4">
                <p class="mb-4">
                    {{ $teaser->description }}
                </p>
            </div>

            <div class="mt-4 flex justify-center md:block px-4">
                <a  href="/teasers" wire:navigate class="text-center text-sm bg-primary-base w-full md:w-60 hover:bg-primary-700 text-white md:text-2xl px-8 py-2 rounded-full">
                    Zurück zur Übersicht
                </a>
            </div>
        </div>
    </div>
</div>
