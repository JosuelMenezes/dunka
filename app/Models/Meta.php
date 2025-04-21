<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meta extends Model
{
    protected $fillable = ['vendedor_id', 'valor_alvo', 'inicio', 'fim'];

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Vendedor::class);
    }
}
