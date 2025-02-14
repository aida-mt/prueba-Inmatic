<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingMovement extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'timestamps'];

    /**
     * Un movimiento contable pertenece a un asiento contable
     */
    public function accountingEntry() {
        return $this->belongsTo(AccountingEntry::class);
    }

    /**
     * Un movimiento contable pertenece a un código contable
     */
    public function accountingCode(){
        return $this->belongsTo(AccountingCode::class);
    }
}
