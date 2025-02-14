<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingEntry extends Model
{
    use HasFactory;

    // Constantes para los estados de un asiento contable
    public const STATUS_DRAFT = 'draft';
    public const STATUS_REGISTERED = 'registered';
    public const STATUS_CANCELLED = 'cancelled';

    protected $guarded = ['id', 'timestamps'];

    /**
     * Un asiento contable pertenece a una factura.
     */
    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Un asiento contable puede tener muchos movimientos contables
     */
    public function movements(){
        return $this->hasMany(AccountingMovement::class);
    }
}
