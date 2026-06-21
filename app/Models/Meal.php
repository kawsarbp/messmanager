<?php

namespace App\Models;

use App\Enums\MealType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meal extends Model
{
    protected $fillable = ['month_id', 'member_id', 'date', 'type', 'quantity'];

    public function month(): BelongsTo
    {
        return $this->belongsTo(Month::class);
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'type' => MealType::class,
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
