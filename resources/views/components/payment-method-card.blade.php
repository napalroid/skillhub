@props(['value', 'label', 'icon', 'checked' => false, 'name' => 'method_type'])

<label class="flex flex-col items-center justify-center p-4 border border-gray-200 cursor-pointer transition-all duration-300 hover:border-gray-900 has-[:checked]:border-gray-900 has-[:checked]:bg-gray-50">
    <input type="radio" name="{{ $name }}" value="{{ $value }}" class="sr-only" @checked($checked)>
    <span class="text-2xl mb-2 leading-none">{{ $icon }}</span>
    <span class="text-[10px] font-medium tracking-wide text-gray-700">{{ $label }}</span>
</label>
