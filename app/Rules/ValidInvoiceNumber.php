<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ValidInvoiceNumber implements ValidationRule
{
    protected const PREFIX = 'F';
    protected const SEPARATOR = '/';
    protected const TOTAL_PARTS = 2;

    /**
     * Run the validation rule.
     *
     * Valida que el número de factura tenga este formato: "F2023/41"
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $invoiceNumber, Closure $fail): void
    {
        if(!Str::contains($invoiceNumber, self::SEPARATOR) || !$this->hasValidFormat($invoiceNumber)) {
            $fail('The invoice number format must be FYYYY/XX.');
            return;
        }

        $parts = $this->splitInvoiceNumber($invoiceNumber);

        if (!$this->hasValidPrefix($parts[0])) {
            $fail('The first letter must be an F.');
            return;
        }

        if (!$this->hasValidYear($parts[0], $fail)) {
            return;
        }

        if (!$this->hasValidNumber($parts[1])) {
            $fail('The last part of the invoice number must be a number.');
            return;
        }
    }

    /**
     * Check if the invoice number has the correct format
     */
    private function hasValidFormat(string $invoiceNumber): bool {
        $parts = $this->splitInvoiceNumber($invoiceNumber);
        return count($parts) === self::TOTAL_PARTS;
    }

    /**
     * Split the invoice number into its components
     */
    private function splitInvoiceNumber(string $invoiceNumber): array {
        return Str::of($invoiceNumber)->explode(self::SEPARATOR)->toArray();
    }

    /**
     * Validate the prefix of the invoice number
     */
    private function hasValidPrefix(string $firstPart): bool {
        return Str::startsWith($firstPart, self::PREFIX);
    }

    /**
     * Validate the year part of the invoice number
     */
    private function hasValidYear(string $firstPart, Closure $fail): bool {
            $year = (int) Str::after($firstPart, self::PREFIX);

            if (!Carbon::canBeCreatedFromFormat($year, 'Y')) {
                $fail('The year is not valid.');
                return false;
            }

            $date = Carbon::createFromFormat('Y', $year);

            if ($date->year > Carbon::now()->year) {
                $fail('The year cannot be in the future.');
                return false;
            }

            // Verificar que el año incluido en el formato, sea igual al año en la fecha de la factura
            $invoiceDate = Carbon::parse(request('date'));
            if ($date->year !== $$invoiceDate->year) {
                $fail('The year in the format must be the same as the year of the invoice.');
                return false;
            }
    }

    /**
     * Validate the number part of the invoice number
     */
    private function hasValidNumber(string $number): bool {
        return ctype_digit($number);
    }
}
