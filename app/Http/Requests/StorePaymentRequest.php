<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
{
    return true; // pengecekan hak akses sudah ditangani middleware
}

public function rules(): array
{
    return [
        'order_id' => 'required|exists:orders,id',
        'proof_file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'amount' => 'required|numeric|min:0',
    ];
}

public function messages(): array
{
    return [
        'proof_file.image' => 'File bukti pembayaran harus berupa gambar.',
        'proof_file.max' => 'Ukuran file maksimal 2MB.',
    ];
}

}