<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Services\AccountingEntryService;

class InvoiceObserver
{
    public function __construct(protected AccountingEntryService $accountingEntryService ) {
    }
    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        $this->validateInvoice($invoice);
        //Crear asiento contable después de guardar la factura
        $this->accountingEntryService->create($invoice);
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void
    {
        //
    }

    /**
     * Valida las condiciones previas para la creación de un asiento contable para la factura.
     *
     * Verifica si la factura está anulada o si ya tiene un asiento contable asociado.
     * En este método se pueden añadir más reglas de validación contable.
     */
    private function validateInvoice(Invoice $invoice): void {
        if ($invoice->status === 'cancelled') {
            throw new \InvalidArgumentException('Accounting entries cannot be created for canceled invoices');
        }

        if ($invoice->accountingEntry()->exists() || $invoice->status === 'accounted') {
            throw new \InvalidArgumentException('An accounting entry already exists for this invoice');
        }
    }
}
