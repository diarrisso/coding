<div>
    <div class=" pt-4 md:pt-12 pb-8 flex justify-center">
        <div class="max-w-[960px] w-full mx-auto md:px-4">
            <div class="relative overflow-hidden md:rounded-2xl h-[300px] md:h-[540px] ">
                <img
                    src="{{ Storage::url($teaser->image_name) }}"
                    alt="Bild"
                    class="w-full md:h-full object-cover bg-gray-100"
                >
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/100 to-transparent text-white">
                    <h3 class="md:text-5xl text-sm sm:text-4xl xs:text-3xl font-bold p-5 md:p-5 sm:p-4 xs:p-3">{{ $teaser->title }}</h3>
                </div>
            </div>

            <div class="mt-6 text-sm text-black dark:text-white mb-11 px-4">
                <p class="mb-4">
                    {{ $teaser->description }}
                </p>
            </div>

            <div class="mt-4 flex justify-center md:block px-4">
                <a  href="/teasers" wire:navigate class="text-center text-sm bg-green-600 w-full md:w-60 hover:bg-green-700 text-white md:text-2xl px-8 py-2 rounded-full">
                    Zurück zur Übersicht
                </a>
            </div>
        </div>
    </div>
</div>
