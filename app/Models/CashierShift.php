<?php

namespace App\Models;

use Database\Factories\CashierShiftFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'cashier_id',
    'started_at',
    'ended_at',
    'starting_cash',
    'total_cash_expected',
    'total_cash_actual',
    'total_sales',
    'status',
    'notes',
])]
class CashierShift extends Model
{
    /** @use HasFactory<CashierShiftFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'starting_cash' => 'decimal:2',
            'total_cash_expected' => 'decimal:2',
            'total_cash_actual' => 'decimal:2',
            'total_sales' => 'decimal:2',
        ];
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
