<?php

namespace App\Models;

use App\Enums\VisibilityStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    protected $fillable = [
        'id',
        'mess_id',
        'user_id',
        'status',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => VisibilityStatus::class,
        ];
    }

    public function mess(): BelongsTo
    {
        return $this->belongsTo(Mess::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
