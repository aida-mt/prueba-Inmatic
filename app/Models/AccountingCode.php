<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingCode extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $guarded = ['id', 'timestamps'];

    /**
     * Un AccountingCode puede tener muchos AccountingMovement
     */
    public function movements(): HasMany {
        return $this->hasMany(AccountingMovement::class);
    }
}
