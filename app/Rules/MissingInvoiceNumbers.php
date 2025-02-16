<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class MissingInvoiceNumbers implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $invoices, Closure $fail): void
    {
        //Asumiendo que las facturas siempre tienen el formato "FYYYY/XX"

        // Validar que haya facturas y tengan el formato correcto
        if (empty($invoices) || !isset($invoices[0]['number'])) {
            $fail('There are no invoices or the format is incorrect.');
            return;
        }

        // Extraer los números de factura FYYYY
        $invoiceFormat = Str::of($invoices[0]['number'])->before('/');
        // Extraer los números de factura XX
        $invoiceNumbers = Arr::map($invoices, function ($invoice) {
            //Si es null o no está definido...
            if (!isset($invoice['number'])) {
                return null;
            }
            return (int) Str::of($invoice['number'])->after('/');
        });

        // Filtrar valores nulos si los hay
        $invoiceNumbers = Arr::whereNotNull($invoiceNumbers);

        // Validar que haya números para comparar
        if (empty($invoiceNumbers)) {
            $fail('No se encontraron números de factura válidos.');
            return;
        }

        // Ordenar los números extraídos
        sort($invoiceNumbers);

        // Generar el rango esperado basado en el primer y último número de la secuencia
        $expectedNumbers = range(head($invoiceNumbers), last($invoiceNumbers));

        // Detectar los números faltantes
        $missingNumbers = array_diff($expectedNumbers, $invoiceNumbers);

        // Si hay números faltantes, la validación falla
        if (!empty($missingNumbers)) {
            $missingInvoices = Arr::map($missingNumbers, fn($num) => $invoiceFormat.'/'.$num);
            $fail('Faltan los números de factura: ' . implode(', ', $missingInvoices));
        }
    }
}
