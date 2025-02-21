<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MissingInvoiceNumbers;
use App\Rules\ValidInvoicesSequence;
use App\Rules\NoDuplicatedInvoiceNumbers;
use App\Rules\ValidInvoiceNumberPattern;

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
        return [
            'invoices' => ['required', 'array', new MissingInvoiceNumbers, new ValidInvoicesSequence, new NoDuplicatedInvoiceNumbers],
            'invoices.*' => 'required|array',
            'invoices.*.number' => ['required','string','unique:invoices', new ValidInvoiceNumberPattern()],
            'invoices.*.supplier' => 'required|string',
            'invoices.*.concepts' => 'required|string',
            'invoices.*.taxable_base' => 'required|numeric',
            'invoices.*.vat' => 'required|numeric',
            'invoices.*.total' => 'required|numeric',
            'invoices.*.date' => 'required|date',
        ];
    }
}
