<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Http\Resources\AccountingEntryResource;
use App\Services\AccountingEntryService;

class InvoiceController extends Controller
{
    public function __construct(protected AccountingEntryService $accountingEntryService ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $request)
    {
        //Validar campos de la factura antes de guardarla
        $validatedInovice = $request->validated();
        $invoice = Invoice::create($validatedInovice);

        //Crear asiento contable después de guardar la factura
        $this->accountingEntryService->create($invoice);

        //Response con los datos del asiento contable creado y sus movimientos
        return new AccountingEntryResource($invoice->accountingEntry);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InvoiceRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
