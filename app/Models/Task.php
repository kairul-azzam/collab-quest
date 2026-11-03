<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'deadline',
        'status',
    ];
    protected function casts(): array
    {
        return [
            'deadline' => 'date:Y-m-d',
        ];
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
