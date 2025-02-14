<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AccountingMovementResource;

class AccountingEntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'date' => $this->date,
            'status' => $this->status,
            // Incluye el número de factura relacionado
            'invoice' => $this->invoice->number,
            // Incluye los movimientos contables relacionados
            'movements' => AccountingMovementResource::collection($this->movements),
        ];
    }
}
