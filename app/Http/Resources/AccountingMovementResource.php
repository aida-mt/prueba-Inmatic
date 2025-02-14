<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountingMovementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Agregar el campo debit sólo si no es null
        if (!is_null($this->debit)) {
            $data['debit'] = $this->debit;
        }
        // Agregar el campo credit sólo si no es null
        if (!is_null($this->credit)) {
            $data['credit'] = $this->credit;
        }
        $data['accounting_code'] = $this->accountingCode->code;

        return $data;
    }
}
