<?php

namespace App\Models;

use App\Enums\RiskLevelEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Risk extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id', // created_by
        'project_id',
        'level',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'project_id' => 'integer',
        'level' => RiskLevelEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
