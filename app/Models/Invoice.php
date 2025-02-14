<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Una factura tiene un único asiento contable
     */
    public function accountingEntry(){
        return $this->hasOne(AccountingEntry::class);
    }
}
