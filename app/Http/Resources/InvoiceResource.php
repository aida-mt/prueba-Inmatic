<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AccountingEntryResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'number' => $this->number,
            'supplier' => $this->supplier,
            'concepts' => $this->concepts,
            'taxable_base' => $this->taxable_base,
            'vat' => $this->vat,
            'total' => $this->total,
            'date' => $this->date,
            'status' => $this->status,
        ];

        // Incluye el asiento contables relacionado si existe
        if (!is_null($this->accountingEntry)) {
            $data['accounting_entry'] = new AccountingEntryResource($this->accountingEntry);
        }
        return $data;
    }
}
