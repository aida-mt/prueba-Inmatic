<?php

namespace App\Queries;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class InvoiceReportQueryBuilder
{
    protected Builder $query;
    protected string $dateFormat;

    /**
     * Inicializa la consulta con el modelo Invoice y el formato de fecha,
     * de está forma, en un futuro el formato puede ser adaptable
     */
    public function __construct(string $dateFormat = '%m/%Y') {
        $this->dateFormat = $dateFormat;
        $this->query = Invoice::query();
    }

    /**
     * Construye la consulta para obtener un resumen periódico por proveedor
     */

    public function getPeriodicalSummaryBySupplier(): Collection {
        return $this->query
            ->selectRaw($this->getSelectColumns())
            ->groupBy($this->getGroupByFields())
            ->orderByRaw("DATE_FORMAT(date, '".$this->dateFormat."')")
            ->orderBy('supplier')->get();
    }

    /**
     * Obtiene las columnas que serán seleccionadas en la consulta
     */
    protected function getSelectColumns(): string {
        return "supplier,
            DATE_FORMAT(date, '".$this->dateFormat."') as date,
            SUM(taxable_base) as total_taxable_base,
            SUM(vat) as total_vat";
    }

    /**
     * Obtiene los campos por los cuales se agruparán los resultados
     */
    protected function getGroupByFields(): array {
        return ['supplier', DB::raw("DATE_FORMAT(date, '".$this->dateFormat."')")];
    }
}
