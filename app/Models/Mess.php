<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mess extends Model
{
    protected $fillable = [
        'id',
        'name',
        'code',
        'address',
        'created_at',
        'updated_at',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function months(): HasMany
    {
        return $this->hasMany(Month::class);
    }

    public function activeMonth(): Month
    {
        $month = $this->months()->where('is_active', true)->first();

        if (!$month) {
            $month = $this->months()->create([
                'label' => now()->format('F Y'),
                'start_date' => now()->toDateString(),
                'is_active' => true,
            ]);
        }

        return $month;
    }
}
