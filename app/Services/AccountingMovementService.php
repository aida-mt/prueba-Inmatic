<?php

namespace App\Services;

use App\Models\AccountingEntry;
use App\Models\Invoice;
use App\Models\AccountingMovement;
use App\Models\AccountingCode;
use Illuminate\Support\Facades\DB;

class AccountingMovementService
{
    protected $movements = [];

    /**
     * Crea los movimientos contables asociados a un asiento contable para una factura dada.
     *
     * 1. Mapea la factura a los movimientos contables correspondientes.
     * 2. Valida que los débitos y créditos estén equilibrados.
     * 3. Crea los movimientos contables en la base de datos.
     */
    public function create(AccountingEntry $accountingEntry, Invoice $invoice): array {
        return DB::transaction(function () use ($accountingEntry, $invoice) {
            $this->movements = $this->mapInvoiceToMovements($invoice);

            $this->validateBalancedMovements($this->movements);
            $createdMovements = [];
            foreach ($this->movements as $movement) {
                $createdMovements[] = AccountingMovement::create([
                    'debit' => $movement['debit'],
                    'credit' => $movement['credit'],
                    'accounting_code_id' => $this->getAccountingCodeId($movement['code']),
                    'accounting_entry_id' => $accountingEntry->id,
                ]);
            }
            return $createdMovements;
        });
    }


    /**
     * Mapea los datos de una factura a movimientos contables
     * Dependiendo del tipo de factura (compra, venta, etc), se generan diferentes movimientos contables.
     */
    private function mapInvoiceToMovements(Invoice $invoice): array {
        $type = $this->getInvoiceType($invoice->supplier);
        // Aquí habría que definir también otros movimientos para otros tipos de factura...
        return match ($type) {
            'compra' => [
                ['debit' => $invoice->taxable_base, 'credit' => null, 'code' => 6000], // Compras (DEBE)
                ['debit' => $invoice->vat, 'credit' => null, 'code' => 4720], // IVA soportado (DEBE)
                ['debit' => null, 'credit' => $invoice->total, 'code' => 4000], // Proveedores (HABER)
            ],
            'venta' => [
                // Definir los movimientos para ventas...
            ],
            default => throw new \InvalidArgumentException("Tipo de factura no válido: ".$type)
        };
    }

    /**
     * Valida que los movimientos contables estén equilibrados (DEBE = HABER).
     */
    private function validateBalancedMovements(array $movements): void
    {
        $debit = collect($movements)->sum('debit');
        $credit = collect($movements)->sum('credit');

        // Comparar débitos y créditos con precisión de 2 decimales
        if (bccomp($debit, $credit, 2) !== 0) {
            throw new \RuntimeException(
                "Los movimientos no están cuadrados. DEBE: ".$debit.", HABER: ".$credit.", Diferencia: " . bcsub($debit, $credit, 2)
            );
        }
    }

    /**
     * Determina el tipo de factura en función de la existencia del proveedor.
     *
     * Nota: Actualmente, se asume que si hay proveedor es una factura de compra.
     * En el futuro podrían añadirse más condiciones para otros tipos de factura.
     */
    private function getInvoiceType($supplier): string  {
        return !empty($supplier) ? 'compra' : 'venta';
    }

    /**
     * Obtiene el ID del código contable correspondiente al código dado.
     */
    private function getAccountingCodeId($code): ?int {
        $accountingCode = AccountingCode::where('code', $code)->firstOrFail();
        return $accountingCode->id;
    }

}
