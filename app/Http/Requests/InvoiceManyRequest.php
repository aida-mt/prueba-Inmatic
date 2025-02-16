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
        $parentRules = parent::rules();
        // Valida que sea un array y tenga al menos dos facturas
        // Hereda las reglas de validación del request original (InvoiceRequest)
        return [
            'invoices' => ['required', 'array', 'min:2', new MissingInvoiceNumbers],
            'invoices.*.' . key($parentRules) => $parentRules[key($parentRules)]
        ];
    }
}
