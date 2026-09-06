<div class="space-y-4">
    @if(count($config['fields'] ?? []) === 0)
    <div class="rounded border border-dashed border-border bg-bg-muted p-6 text-center">
        <p class="text-sm text-text-secondary">Tidak ada field booking untuk jasa ini.</p>
    </div>
    @else
    @foreach($config['fields'] ?? [] as $field)
    <div wire:key="field-{{ $field['name'] }}" class="field-wrapper">
        <label for="field-{{ $field['name'] }}" class="block text-sm font-bold uppercase tracking-wide text-black mb-2">
            {{ $field['label'] }}
            @if($field['required'])
            <span class="text-red-600">*</span>
            @endif
        </label>

        @switch($field['type'])
            @case('text')
                <input 
                    type="text" 
                    id="field-{{ $field['name'] }}"
                    wire:model="formData.{{ $field['name'] }}"
                    placeholder="{{ $field['placeholder'] ?? '' }}"
                    maxlength="{{ $field['maxlength'] ?? 255 }}"
                    {{ $readOnly ? 'readonly' : '' }}
                    class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none {{ $readOnly ? 'bg-bg-muted' : '' }}"
                >
                @break

            @case('textarea')
                <textarea 
                    id="field-{{ $field['name'] }}"
                    wire:model="formData.{{ $field['name'] }}"
                    placeholder="{{ $field['placeholder'] ?? '' }}"
                    rows="4"
                    {{ $readOnly ? 'readonly' : '' }}
                    class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none resize-y {{ $readOnly ? 'bg-bg-muted' : '' }}"
                ></textarea>
                @break

            @case('number')
                <input 
                    type="number" 
                    id="field-{{ $field['name'] }}"
                    wire:model="formData.{{ $field['name'] }}"
                    placeholder="{{ $field['placeholder'] ?? '' }}"
                    min="{{ $field['min'] ?? '' }}"
                    max="{{ $field['max'] ?? '' }}"
                    step="{{ $field['step'] ?? '1' }}"
                    {{ $readOnly ? 'readonly' : '' }}
                    class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none {{ $readOnly ? 'bg-bg-muted' : '' }}"
                >
                @break

            @case('select')
                <select 
                    id="field-{{ $field['name'] }}"
                    wire:model="formData.{{ $field['name'] }}"
                    {{ $readOnly ? 'disabled' : '' }}
                    class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none {{ $readOnly ? 'bg-bg-muted' : '' }}"
                >
                    <option value="">-- Pilih {{ $field['label'] }} --</option>
                    @foreach($field['options'] ?? [] as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
                @break

            @case('radio')
                <div class="space-y-2">
                    @foreach($field['options'] ?? [] as $option)
                    <label class="flex items-center gap-2">
                        <input 
                            type="radio" 
                            wire:model="formData.{{ $field['name'] }}"
                            value="{{ $option }}"
                            {{ $readOnly ? 'disabled' : '' }}
                            class="text-[#0051BA] focus:ring-[#0051BA]"
                        >
                        <span class="text-sm">{{ $option }}</span>
                    </label>
                    @endforeach
                </div>
                @break

            @case('checkbox')
                <label class="flex items-center gap-2">
                    <input 
                        type="checkbox" 
                        id="field-{{ $field['name'] }}"
                        wire:model="formData.{{ $field['name'] }}"
                        {{ $readOnly ? 'disabled' : '' }}
                        class="text-[#0051BA] focus:ring-[#0051BA]"
                    >
                    <span class="text-sm">{{ $field['help_text'] ?? 'Ya' }}</span>
                </label>
                @break

            @case('date')
                <input 
                    type="date" 
                    id="field-{{ $field['name'] }}"
                    wire:model="formData.{{ $field['name'] }}"
                    {{ $readOnly ? 'readonly' : '' }}
                    class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none {{ $readOnly ? 'bg-bg-muted' : '' }}"
                >
                @break

            @case('time')
                <input 
                    type="time" 
                    id="field-{{ $field['name'] }}"
                    wire:model="formData.{{ $field['name'] }}"
                    {{ $readOnly ? 'readonly' : '' }}
                    class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none {{ $readOnly ? 'bg-bg-muted' : '' }}"
                >
                @break

            @case('password')
                <input 
                    type="password" 
                    id="field-{{ $field['name'] }}"
                    wire:model="formData.{{ $field['name'] }}"
                    placeholder="{{ $field['placeholder'] ?? '' }}"
                    {{ $readOnly ? 'readonly' : '' }}
                    class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none {{ $readOnly ? 'bg-bg-muted' : '' }}"
                >
                @if(!empty($field['help_text']))
                <p class="mt-1 text-xs text-text-secondary">{{ $field['help_text'] }}</p>
                @endif
                @break

            @default
                <input 
                    type="text" 
                    id="field-{{ $field['name'] }}"
                    wire:model="formData.{{ $field['name'] }}"
                    placeholder="{{ $field['placeholder'] ?? '' }}"
                    {{ $readOnly ? 'readonly' : '' }}
                    class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none {{ $readOnly ? 'bg-bg-muted' : '' }}"
                >
        @endswitch

        @error("formData.{$field['name']}")
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror

        @if(!empty($field['help_text']) && $field['type'] !== 'password')
        <p class="mt-1 text-xs text-text-secondary">{{ $field['help_text'] }}</p>
        @endif
    </div>
    @endforeach

    @if(!$readOnly)
    <div class="pt-4">
        <button type="button" wire:click="saveBookingData" class="w-full rounded bg-[#0051BA] px-6 py-3 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-black">
            Simpan Data Booking
        </button>
    </div>
    @endif
    @endif
</div>

@script
<script>
    $wire.on('alert', (event) => {
        const data = event[0] || event;
        alert(data.message);
    });
</script>
@endscript
