<?php

namespace App\Livewire\Booking;

use App\Models\Service;
use App\Models\Order;
use Livewire\Component;

class DynamicFormRenderer extends Component
{
    public Service $service;
    public ?Order $order = null;
    public array $config = [];
    public array $formData = [];
    public bool $readOnly = false;

    public function mount(Service $service, ?Order $order = null, bool $readOnly = false)
    {
        $this->service = $service;
        $this->order = $order;
        $this->readOnly = $readOnly;
        $this->config = $service->booking_config ?? [];
        
        if ($order && $order->booking_data) {
            $this->formData = $order->booking_data;
        } else {
            $this->initializeFormData();
        }
    }

    public function render()
    {
        return view('livewire.booking.dynamic-form-renderer');
    }

    protected function initializeFormData()
    {
        foreach ($this->config['fields'] ?? [] as $field) {
            $this->formData[$field['name']] = $field['default'] ?? null;
        }
    }

    public function saveBookingData()
    {
        if ($this->readOnly) {
            return;
        }

        $this->validate($this->getValidationRules());

        if ($this->order) {
            $this->order->update(['booking_data' => $this->formData]);
            $this->dispatch('bookingSaved', orderId: $this->order->id);
        }

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Data booking berhasil disimpan']);
    }

    public function getBookingData()
    {
        return $this->formData;
    }

    protected function getValidationRules(): array
    {
        $rules = [];
        
        foreach ($this->config['fields'] ?? [] as $field) {
            $key = 'formData.' . $field['name'];
            $fieldRules = [];

            if ($field['required']) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            switch ($field['type']) {
                case 'number':
                    $fieldRules[] = 'integer';
                    if (isset($field['min'])) $fieldRules[] = 'min:' . $field['min'];
                    if (isset($field['max'])) $fieldRules[] = 'max:' . $field['max'];
                    break;

                case 'text':
                    $fieldRules[] = 'string';
                    if (isset($field['maxlength'])) {
                        $fieldRules[] = 'max:' . $field['maxlength'];
                    } else {
                        $fieldRules[] = 'max:255';
                    }
                    break;

                case 'textarea':
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:2000';
                    break;

                case 'select':
                case 'radio':
                    if (!empty($field['options'])) {
                        $fieldRules[] = 'in:' . implode(',', $field['options']);
                    }
                    break;

                case 'checkbox':
                    $fieldRules[] = 'boolean';
                    break;

                case 'date':
                    $fieldRules[] = 'date';
                    break;

                case 'time':
                    $fieldRules[] = 'date_format:H:i';
                    break;

                case 'password':
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'min:6';
                    break;

                default:
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:1000';
            }

            $rules[$key] = implode('|', $fieldRules);
        }

        return $rules;
    }

    protected function messages(): array
    {
        $messages = [];
        
        foreach ($this->config['fields'] ?? [] as $field) {
            $key = 'formData.' . $field['name'];
            $messages[$key . '.required'] = $field['label'] . ' wajib diisi';
            $messages[$key . '.min'] = $field['label'] . ' minimal ' . ($field['min'] ?? 0);
            $messages[$key . '.max'] = $field['label'] . ' maksimal ' . ($field['max'] ?? 255);
        }

        return $messages;
    }
}
