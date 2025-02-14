<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        $this->validateInvoice($invoice);
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
            throw new \InvalidArgumentException('No se pueden crear asientos para facturas anuladas');
        }

        if ($invoice->accountingEntry()->exists() || $invoice->status === 'accounted') {
            throw new \InvalidArgumentException('Ya existe un asiento contable para esta factura');
        }
    }
}
