<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\InvoicesRequest;
use App\Http\Requests\SearchInvoiceRequest;
use App\Models\Invoice;
use App\Http\Resources\AccountingEntryResource;
use App\Http\Resources\InvoiceResource;
use App\Queries\InvoiceReportQueryBuilder;
use Illuminate\Support\Facades\DB;

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
    public function store(InvoicesRequest $request)
    {
        //Validar campos de la factura antes de guardarla
        $validatedInvoice = $request->validated();
        $invoice = Invoice::create($validatedInvoice);

        //Response con los datos del asiento contable creado y sus movimientos
        return new AccountingEntryResource($invoice->accountingEntry);
    }

    /**
     * Store multiple newly created invoices in storage.
     */
    public function bulkStore(InvoicesRequest $request) {
        //Todavía en desarrollo..

        //Validar las facturas antes de guardarlas
        // $validatedInvoices = $request->validated();

        // $invoices = Invoice::insert($validatedInvoices);

        // Devolver las facturas creadas como un recurso
        // return response()->json([
        //     'message' => 'Invoices created successfully',
        //     'total_created' => count($invoices),
        // ], 201);
    }

    /**
     * Display the summary of invoices grouped by supplier and date.
     */
    public function summary (InvoiceReportQueryBuilder $builder){
        /**
         * Esta es la forma en que se implementaría para recibir el formato dinámicamente, implementando sus respectias validaciones
         * public function summary (Request $request){
         * $format = $request->input('format',);
         * $builder = new InvoiceReportQueryBuilder($format );
         * Si no se proporciona, se usaría el valor predeterminado
         */
        $invoices = $builder->getPeriodicalSummaryBySupplier();

        if ($invoices->isEmpty()) {
            return response()->json(['message' => 'No data available.'], 404);
        }
        return response()->json(['summary' => $invoices], 200);
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
    public function update(InvoicesRequest $request, string $id)
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
