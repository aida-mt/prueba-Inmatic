<?php

namespace App\Models;

use App\Observers\InvoiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([InvoiceObserver::class])]
class Invoice extends Model
{
    use HasFactory;

    // Definición de constantes para los estados de una factura
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACCOUNTED = 'accounted';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELLED = 'cancelled';

    protected $guarded = ['id', 'timestamps'];

    /**
     * Valor predeterminado para el campo 'status' como 'draft'
     * no pasará a 'accounted' hasta que el asiento contable y sus movimientos hayan sido generados
     */
    protected $attributes = [ 'status' => self::STATUS_DRAFT ];

    /**
     * Relación: Una factura tiene un único asiento contable
     */
    public function accountingEntry(): HasOne{
        return $this->hasOne(AccountingEntry::class);
    }

    /**
     * Scope para filtrar las facturas por estado.
     * Permite filtrar las facturas por estados (draft, accounted, paid, cancelled)
     */
    public function scopeStatus(Builder $query, ?array $status): Builder {
        return $query->when($status, function ($query) use ($status) {
            $query->whereIn('status', $status);
        });
    }

    /**
     * Scope para filtrar las facturas por fecha.
     * Permite filtrar las facturas dentro de un rango de fechas (date_from - date_to), o por una fecha concreta (date_from = date_to).
     */
    public function scopeDateFilter(Builder $query, ?string $dateFrom, ?string $dateTo): Builder {
        return $query->when($dateFrom || $dateTo, function ($query) use ($dateFrom, $dateTo) {
            // Si tenemos un rango de fechas con 'date_from' y 'date_to'
            if ($dateFrom && $dateTo) {
                $query->where('date', '>=', $dateFrom)->where('date', '<=', $dateTo);
            }
            // Si sólo tenemos 'date_from', facuras con fecha igual o posterior a 'date_from'
            elseif ($dateFrom) {
                $query->where('date', '>=', $dateFrom);
            }
            // Si sólo tenemos 'date_to', facuras con fecha igual o anterior a 'date_to'
            elseif ($dateTo) {
                $query->where('date', '<=', $dateTo);
            }
        });
    }

    /**
     * Scope que combina todos los filtros (estado y fecha) en una sola consulta
     */
    public function scopeSearch(Builder $query, array $filters = []): Builder
    {
        return $query
            ->when(
                isset($filters['date_from']) || isset($filters['date_to']),
                fn ($query) => $query->dateFilter($filters['date_from'] ?? null, $filters['date_to'] ?? null)
            )
            ->when(
                isset($filters['status']),
                fn ($query) => $query->status($filters['status'])
            );
    }
}
