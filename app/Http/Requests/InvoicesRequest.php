<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MissingInvoiceNumbers;
use App\Rules\ValidInvoiceNumber;

class InvoicesRequest extends FormRequest
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
        /** Valida:
         * Que sea un array y tenga al menos dos facturas.
         * Que la colleción sea correcta
         * No falten numeros de factura.
        */
        return [
            'invoices' => ['required', 'array', new MissingInvoiceNumbers, ],
            'invoices.*' => 'required|array',
            'invoices.*.number' => ['required','string','unique:invoices', new ValidInvoiceNumber()],
            'invoices.*.supplier' => 'required|string',
            'invoices.*.concepts' => 'required|string',
            'invoices.*.taxable_base' => 'required|numeric',
            'invoices.*.vat' => 'required|numeric',
            'invoices.*.total' => 'required|numeric',
            'invoices.*.date' => 'required|date',
        ];
    }
}
