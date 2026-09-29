<?php

namespace App\Livewire\Booking;

use App\Models\Service;
use Livewire\Component;
use Illuminate\Support\Str;

class ConfigBuilder extends Component
{
    public Service $service;
    public array $fields = [];
    public array $templates = [];
    public bool $showTemplateModal = false;
    public $timeSlotsEnabled = false;

    public $newField = [
        'name' => '',
        'type' => 'text',
        'label' => '',
        'required' => false,
        'options' => [],
        'placeholder' => '',
        'help_text' => '',
        'min' => null,
        'max' => null,
    ];

    protected $rules = [
        'fields.*.name' => 'required|string|max:50',
        'fields.*.type' => 'required|string',
        'fields.*.label' => 'required|string|max:100',
        'fields.*.required' => 'boolean',
    ];

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->fields = $service->booking_config['fields'] ?? [];
        $this->templates = config('booking.templates', []);
        
        $rawValue = $service->time_slots_enabled;
        $this->timeSlotsEnabled = $rawValue ? 1 : 0;
        
        \Log::info('ConfigBuilder mount', [
            'service_id' => $service->id,
            'time_slots_enabled_raw' => $rawValue,
            'time_slots_enabled_type' => gettype($rawValue),
            'timeSlotsEnabled_prop' => $this->timeSlotsEnabled,
            'timeSlotsEnabled_type' => gettype($this->timeSlotsEnabled)
        ]);
    }

    public function render()
    {
        return view('livewire.booking.config-builder');
    }

    public function updated($property)
    {
        if ($property === 'timeSlotsEnabled') {
            $intValue = (int) $this->timeSlotsEnabled;
            $boolValue = $intValue === 1;
            
            \Log::info('Time slots updated', [
                'service_id' => $this->service->id,
                'new_value_raw' => $this->timeSlotsEnabled,
                'new_value_int' => $intValue,
                'new_value_bool' => $boolValue
            ]);
            
            $updated = $this->service->update(['time_slots_enabled' => $boolValue]);
            
            \Log::info('Update result', ['success' => $updated]);
            
            $fresh = $this->service->fresh();
            \Log::info('After save', [
                'saved_value' => $fresh->time_slots_enabled,
                'saved_value_raw' => $fresh->getRawOriginal('time_slots_enabled')
            ]);
            
            $this->timeSlotsEnabled = $fresh->time_slots_enabled ? 1 : 0;
            
            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'Time slots ' . ($boolValue ? 'diaktifkan' : 'dinonaktifkan')
            ]);
        }
    }

    public function logChange($value)
    {
        \Log::info('Dropdown changed', ['value' => $value]);
    }

    public function addField()
    {
        if (count($this->fields) >= config('booking.max_fields_per_service', 15)) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Maksimal 15 field per jasa']);
            return;
        }

        if (empty($this->newField['label'])) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Label wajib diisi']);
            return;
        }

        $fieldName = Str::slug($this->newField['label'], '_');
        
        if (collect($this->fields)->pluck('name')->contains($fieldName)) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Field dengan label tersebut sudah ada']);
            return;
        }

        // Convert options string to array for select/radio/checkbox
        $field = array_merge($this->newField, ['name' => $fieldName]);
        
        if (in_array($field['type'], ['select', 'radio', 'checkbox'])) {
            if (is_string($field['options'])) {
                $field['options'] = array_map('trim', explode(',', $field['options']));
                $field['options'] = array_filter($field['options']); // Remove empty values
            }
        } else {
            $field['options'] = [];
        }

        $this->fields[] = $field;
        
        $this->reset('newField');
        $this->newField = [
            'name' => '',
            'type' => 'text',
            'label' => '',
            'required' => false,
            'options' => [],
            'placeholder' => '',
            'help_text' => '',
            'min' => null,
            'max' => null,
        ];
    }

    public function removeField($index)
    {
        logger('removeField called with index: ' . $index);
        logger('Fields before: ' . json_encode($this->fields));
        
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
        
        logger('Fields after: ' . json_encode($this->fields));
    }

    public function moveField($index, $direction)
    {
        if ($direction === 'up' && $index > 0) {
            $temp = $this->fields[$index - 1];
            $this->fields[$index - 1] = $this->fields[$index];
            $this->fields[$index] = $temp;
        } elseif ($direction === 'down' && $index < count($this->fields) - 1) {
            $temp = $this->fields[$index + 1];
            $this->fields[$index + 1] = $this->fields[$index];
            $this->fields[$index] = $temp;
        }
    }

    public function applyTemplate($templateKey)
    {
        $template = $this->templates[$templateKey] ?? null;
        
        if (!$template) {
            return;
        }

        $this->fields = array_merge($this->fields, $template['fields']);
        $this->showTemplateModal = false;
        
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => "Template '{$template['name']}' berhasil diterapkan"
        ]);
    }

    public function saveConfig()
    {
        $names = array_column($this->fields, 'name');
        if (count($names) !== count(array_unique($names))) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Nama field harus unik']);
            return;
        }

        $this->service->update([
            'booking_config' => [
                'enabled' => true,
                'fields' => array_values($this->fields),
            ],
            'last_booking_config_edit' => now(),
        ]);

        $message = 'Konfigurasi booking berhasil disimpan';

        $this->dispatch('alert', [
            'type' => 'success', 
            'message' => $message
        ]);
        $this->dispatch('configSaved');
    }

    public function clearConfig()
    {
        $this->fields = [];
    }
}
