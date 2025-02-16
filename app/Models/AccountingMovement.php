<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountingMovement extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'timestamps'];

    /**
     * Un movimiento contable pertenece a un asiento contable
     */
    public function accountingEntry(): BelongsTo {
        return $this->belongsTo(AccountingEntry::class);
    }

    /**
     * Un movimiento contable pertenece a un código contable
     */
    public function accountingCode(): belongsTo{
        return $this->belongsTo(AccountingCode::class);
    }
}
