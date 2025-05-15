
<div x-data="{ showTeaserForm: false }" class="max-w-[960px] mx-auto px-4 py-8 mt-0 xl:mt-15">
    <div class="flex justify-center md:justify-end items-center mb-6">
        <button
            @click="showTeaserForm = !showTeaserForm; $nextTick(() => showTeaserForm ? $el.querySelector('#create-form').scrollIntoView({ behavior: 'smooth' }) : null)"
            class="inline-flex  items-center px-4 py-2 bg-primary-base border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring ring-primary-300 transition"
        >
            <svg x-show="!showTeaserForm" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <svg x-show="showTeaserForm" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span x-text="showTeaserForm ? 'Schließen Sie das Formular' : 'Fügen Sie einen Teaser hinzu'" class=" text-sm md:text-2xl"></span>
        </button>
    </div>


    @if (session()->has('message'))
        <div class="bg-primary-100 border-l-4 border-primary-500 text-primary-500 p-4 mb-6" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif


    <div class="space-y-3 mb-8" x-show="!showTeaserForm">
        @forelse($teasers as $teaser)
            <a href="{{ route('teasers.show', $teaser) }}" class="block">
                <div class="bg-white shadow-sm border border-gray-300 rounded-lg overflow-hidden p-4 hover:shadow-md transition">
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-bold mb-3 hidden md:block text-black ">{{ $teaser->title }}</h3>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/3 order-1">
                            <div class="aspect-[16/9]">
                                <img
                                    src="{{ Storage::url($teaser->image_name) }}"
                                    alt="{{ $teaser->title }}"
                                    class="w-full h-full object-cover rounded"
                                >
                            </div>
                        </div>

                        <div class="w-full md:w-2/3 flex flex-col order-2">
                            <h3 class="text-xl sm:text-2xl font-bold mb-2 mt-3 md:hidden text-black ">{{ $teaser->title }}</h3>
                            <div class="text-black  text-sm sm:text-base leading-relaxed">
                                {{ $teaser->description }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <p class="text-center text-gray-500 py-6">Noch keine Teaser vorhanden.</p>
        @endforelse
    </div>


    <div
        id="create-form"
        x-show="showTeaserForm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-4"
    >
       <livewire:teaser-form />
    </div>

</div>

@push('scripts')
@endpush
