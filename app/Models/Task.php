<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'board_id',
        'parent_id',
        'title',
        'description',
        'status',
        'priority',
        'deadline',
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }
}
