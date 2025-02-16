<?php

namespace App\Services;

use App\Models\AccountingEntry;
use App\Models\Invoice;
use App\Services\AccountingMovementService;
use Illuminate\Support\Facades\DB;

class AccountingEntryService
{
    /**
     * Constructor del servicio de asientos contables
     * Inicializa el servicio encargado de la creación de los movimientos contables.
    */
    public function __construct(protected AccountingMovementService $movementService ) {

    }

    /**
     * Crea un asiento contable para la factura guardada.
     *
     * Este método crea un nuevo asiento contable con el estado 'draft', vinculado a la factura.
     * Se generan los movimientos contables asociados al asiento.
     * Si no hay errores, el estado del asiento se actualiza a 'registered' y el de la factura a 'accounted'.
     */
    public function create(Invoice $invoice): AccountingEntry {
        return DB::transaction(function () use ($invoice) {
            $accountingEntry = AccountingEntry::create([
                'description' => $invoice->concepts,
                'status' => AccountingEntry::STATUS_DRAFT,
                'invoice_id' => $invoice->id
            ]);
            try{
                // Crea los movimientos contables asociados al asiento
                $accountingMovements = $this->movementService->create($accountingEntry, $invoice);
                // Si los asientpos han sido creados y el estado del asiento contable es 'draft'
                // Actualiza el estado del asiento a 'registered' y el estado de la factura a 'accounted'
                if ($accountingMovements && $accountingEntry->status === AccountingEntry::STATUS_DRAFT) {
                    $accountingEntry->update(['status' => AccountingEntry::STATUS_REGISTERED]);
                    $invoice->update(['status' => Invoice::STATUS_ACCOUNTED]);
                }
                return $accountingEntry;

            }catch(\Exception $e) {
                throw new \RuntimeException("Error creating accounting movements: ".$e->getMessage());
            }
        });
    }
}
