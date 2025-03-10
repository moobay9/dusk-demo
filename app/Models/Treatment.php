<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Casts\MoneyCast;

class Treatment extends Model
{
    protected $casts = [
        'price' => MoneyCast::class,
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

}
