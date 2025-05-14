<div>
    <div class="pt-12 pb-8 flex justify-center">
        <div class="max-w-[960px] w-full mx-auto px-4">
            <div class="relative overflow-hidden rounded-2xl  ">
                <img
                    src="{{ Storage::url($teaser->image_name) }}"
                    alt="Bild"
                    class="w-full aspect-video object-cover bg-gray-100 rounded shadow "
                >
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/100 to-transparent text-white ">
                    <h3 class="text-5xl font-bold p-5">{{ $teaser->title }}</h3>
                </div>
            </div>

            <div class="mt-6 text-sm text-black dark:text-white mb-11">
                <p class="mb-4">
                    {{ $teaser->description }}
                </p>
            </div>

            <div class="mt-4">
                <a  href="/teasers" wire:navigate class="bg-green-600 w-full md:w-60 hover:bg-green-700 text-white text-2xl px-8 py-2 rounded-full">
                    Zurück zur Übersicht
                </a>
            </div>
        </div>
    </div>
</div>
