<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class MissingInvoiceNumbers implements ValidationRule
{
    protected const SEPARATOR = '/';

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $invoices, Closure $fail): void
    {
        //Asumiendo que las facturas siempre tienen el formato "FYYYY/XX"

        // Extraer los números de factura FYYYY
        $invoiceYearString = $this->extractinvoiceYearString($invoices[0]['number']);
        // Extraer los números de factura XX
        $invoiceNumbers = $this->extractInvoiceNumbers($invoices);

        // Devuelve los números faltantes de la secuencia
        $missingNumbers = $this->findMissingNumbers($invoiceNumbers);

        // Si hay números faltantes, la validación falla
        if (!empty($missingNumbers)) {
            $missingInvoices = $this->formatMissingInvoices($missingNumbers, $invoiceYearString);
            $fail('Missing invoice numbers: ' . implode(', ', $missingInvoices));
        }
    }

    /**
     * Extract the first part (FYYYY) from invoice number
     */
    private function extractinvoiceYearString(string $invoiceNumber): string {
        return Str::of($invoiceNumber)->before(self::SEPARATOR);
    }

    /**
     * Extract the second part (XX) and clean sequential numbers from invoices
     */
    private function extractInvoiceNumbers(array $invoices): array {
        return Arr::map($invoices, function ($invoice) {
            return (int) (string) Str::of($invoice['number'])->after(self::SEPARATOR);
        });

        // return $validNumbers;
    }

    /**
     * Find missing numbers in the sequence
     */
    private function findMissingNumbers(array $invoiceNumbers): array {
        // Generar el rango esperado basado en el primer y último número de la secuencia
        $expectedNumbers = range(min($invoiceNumbers), max($invoiceNumbers));
        // Devuelve los números faltantes
        return array_values(array_diff($expectedNumbers, $invoiceNumbers));
    }

    /**
     * Format missing invoice numbers with the original format
     */
    private function formatMissingInvoices(array $missingNumbers, string $invoiceFormat): array {
        // Generar un string con el listado de los números de factura faltantes
        return Arr::map(
            $missingNumbers,
            fn($num) => $invoiceFormat . self::SEPARATOR . $num
        );
    }
}
