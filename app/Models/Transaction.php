<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    //
    protected $fillable = [
        'category_id',
        'amount',
        'description',
        'transaction_date'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',

    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }
}
