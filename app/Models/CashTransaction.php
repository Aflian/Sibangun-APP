<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['branch_id', 'user_id', 'type', 'reference_type', 'reference_id', 'payment_method', 'amount', 'transaction_date', 'description', 'created_at'])]
class CashTransaction extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'transaction_date' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
