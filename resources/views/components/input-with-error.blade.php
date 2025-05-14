
@props(['name', 'label', 'type' => 'text'])
<div>
    <label for="{{ $name }}" class="block text-gray-500 mb-2">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $name }}" wire:model.live="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full border rounded-md p-2.5 focus:outline-none focus:ring-2 focus:ring-green-500 ' .
                    ($errors->has($name) ? 'border-red-500' : 'border-gray-300')]) }}>
    @error($name)
    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
    @enderror
</div>
