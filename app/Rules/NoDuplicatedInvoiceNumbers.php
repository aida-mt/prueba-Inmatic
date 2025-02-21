<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoDuplicatedInvoiceNumbers implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $invoiceNumbers = collect($value)->pluck('number'); // Extrae todos los números de factura
        // Encuentra los números que aparecen más de una vez
        $duplicates = $invoiceNumbers->countBy()->filter(function ($count) {
            return $count > 1;
        })->keys();

        if ($duplicates->isNotEmpty()) {
            $fail('The following invoice numbers are duplicated: '.$duplicates->implode(', '));
        }
    }
}
