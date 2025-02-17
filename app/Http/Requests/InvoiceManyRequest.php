<?php

namespace App\Http\Requests;

use App\Rules\MissingInvoiceNumbers;

class InvoiceManyRequest extends InvoiceRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Valida que sea un array y tenga al menos dos facturas
        return [
            'invoices' => ['required', 'array', 'min:2', new MissingInvoiceNumbers],
            'invoices.*' => 'required|array',
        ];
    }
}
