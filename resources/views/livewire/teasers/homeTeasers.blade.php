<div>
    @if (Auth::user()->isAdmin())
        <div
            x-data="{
            show: false,
            init() {
                setTimeout(() => this.show = true, 100);
            }
        }"
            x-show="show"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            @forelse($teasers as $teaser)
                <div
                    x-data="{ hover: false }"
                    @mouseenter="hover = true"
                    @mouseleave="hover = false"
                    :class="{ 'transform scale-[1.02] shadow-lg': hover }"
                    class="bg-white rounded-lg overflow-hidden shadow border border-gray-200 transition-all duration-300"
                >
                    <a href="{{ route('teasers.show', $teaser) }}" class="block">
                        <div class="relative aspect-video bg-gray-200 overflow-hidden">
                            @if($teaser->image_name)
                                <img
                                    src="{{ Storage::url($teaser->image_name) }}"
                                    alt="{{ $teaser->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500"
                                    :class="{ 'transform scale-110': hover }"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <h3 class="text-2xl md:text-3xl font-bold mb-2 ">{{ $teaser->title }}</h3>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full py-10 text-center bg-gray-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2 text-gray-500">Keine Teaser gefunden.</p>
                </div>
            @endforelse
        </div>

        @if($hasMoreTeasers)
            <div class="mt-10 text-center">
                <button
                    wire:click="loadMoreTeasers"
                    wire:loading.attr="disabled"
                    class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-md inline-flex items-center transition-colors"
                >
                    <span wire:loading.remove>Mehr anzeigen</span>
                    <span wire:loading>
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Wird geladen...
                </span>
                </button>
            </div>
        @endif
    @else
        <div class="text-3xl md:text-5xl font-bold ">Sie sind nicht berechtigt, diese Seite zu sehen. Nur für Benutzer mit der Rolle Admin.</div>
    @endif

</div>
