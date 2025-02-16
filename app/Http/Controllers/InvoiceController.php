<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\InvoiceRequest;
use App\Http\Requests\SearchInvoiceRequest;
use App\Models\Invoice;
use App\Http\Resources\AccountingEntryResource;
use App\Http\Resources\InvoiceResource;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Search a filtered listing of the resource.
     */
    public function search(SearchInvoiceRequest $request)
    {
        // Obtiene y valida los filtros de la solicitud
        $filters = $request->validated();
        // Aplica los filtros al modelo Invoice usando el scope 'search'
        // Ordena los resultados de forma descendente por la fecha de creación (latest)
        // Pagina los resultados, mostrando 5 facturas por página
        $invoices = Invoice::search($filters)->latest()->paginate(5);

        // Modifica los enlaces de paginación para incluir los filtros de la consulta GET
        $invoices->appends($filters);

        // Devuelve los resultados formateados con la resource
        return InvoiceResource::collection($invoices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $request)
    {
        //Validar campos de la factura antes de guardarla
        $validatedInovice = $request->validated();
        $invoice = Invoice::create($validatedInovice);

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
