<div class="py-6">
    <div class="max-w-[960px] mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                @if (Auth::id() === $teaser->user_id)
                    <livewire:teaser-update :teaser="$teaser" />
                @else
                    <div class="p-4 bg-yellow-100 text-yellow-700 rounded-md">
                        Sie sind nicht berechtigt, diesen Teaser zu bearbeiten.
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
