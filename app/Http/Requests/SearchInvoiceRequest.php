<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class SearchInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void {
        if ($this->has('status')) {
            $this->merge(['status' => explode(',', $this->input('status'))]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array{
        return [
            'status.*' => 'nullable|in:draft,accounted,paid,cancelled',
            'date_from' => 'bail|nullable|date|date_format:Y-m-d|before_or_equal:today',
            'date_to' => 'bail|nullable|date|date_format:Y-m-d|before_or_equal:today',
        ];
    }

    /**
     * Configure the validator instance.
     * Se agrega una validación personalizada para comprobar que 'date_from'
     * no sea mayor que 'date_to' cuando ambos campos están presentes.
     */
    public function after(): array {
        return [
            function (Validator $validator) {
                // Sólo validar si ambos campos 'date_from' y 'date_to' existen y además no hay errores previos
                if ($this->has('date_from') && $this->has('date_to') && $validator->getMessageBag()->isEmpty()) {
                    // Parsear fechas con Carbon
                    $startDate = Carbon::parse($this->input('date_from'));
                    $endDate = Carbon::parse($this->input('date_to'));

                    // Comprueba que la fecha de inicio no sea posterior a la fecha de fin, agrega error en caso afirmativo
                    if ($startDate->greaterThan($endDate)) {
                        $validator->errors()->add('date_from', 'The start date must be less than or equal to the end date.');
                    }
                }
            }
        ];
    }

    /**
     * Lanza una excepción con un mensaje de error en formato JSON cuando la validación falla.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) {
        throw new ValidationException($validator, response()->json([ 'errors' => $validator->errors() ], 422));
    }
}
