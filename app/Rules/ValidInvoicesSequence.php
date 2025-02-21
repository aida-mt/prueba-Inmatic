<?php

namespace App\Rules;

use Closure;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class ValidInvoicesSequence implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $invoices, Closure $fail): void
    {
        $invoicesByDate = $this->getSortedInvoiceNumbers($invoices);
        $invalidInvoiceNumbers = $this->isValidSequence($invoicesByDate);
        if (!empty($invalidInvoiceNumbers)) {
            $fail('The invoices: '.$invalidInvoiceNumbers.' have an incorrect order. ');
        }
    }

    /**
     * Convierte las facturas en un array con el número secuencial del número de factura, la fecha y el nñumero de factura, y lo ordena por fecha
     */
    private function getSortedInvoiceNumbers(array $invoices): Collection
    {
        return collect($invoices)->map(function ($invoice) {
                return [
                    'sequential_number' => (int) Str::of($invoice['number'])->after('/'),
                    'date' => Carbon::createFromFormat('Y-m-d', $invoice['date']),
                    'invoice_number' => $invoice['number']
                ];
            })->sortBy('date');
    }

    /**
     * Verifica el orden de la secuencia de números de factura coincida con el orden de las facturas por fecha
     */
    private function isValidSequence(Collection $invoices): string
    {
        $numberSequence = $invoices->pluck('sequential_number')->toArray();

        // Ordena los numberSequence en orden ascendente
        $expectedNumberSequence = collect($numberSequence)->sort()->values()->toArray();

        // Si las secuencias son iguales, todo está correcto
        if ($numberSequence === $expectedNumberSequence) {
            return '';
        }

        // Obtiene los números que están en posiciones incorrectas
        $invalidNumbers = $invoices ->filter(function ($invoice, $index) use ($numberSequence, $expectedNumberSequence) {
            return $numberSequence[$index] !== $expectedNumberSequence[$index];
        })->pluck('invoice_number')->implode(', ');

        return $invalidNumbers;
    }

}
