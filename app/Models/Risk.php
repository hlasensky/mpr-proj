<?php

namespace App\Models;

use App\Enums\RiskLevelCategoryEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Risk extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'project_id',
        'impact',
        'likelihood',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'project_id' => 'integer',
        'impact' => 'integer',
        'likelihood' => 'integer',
    ];

    public function score(): int
    {
        return $this->impact * $this->likelihood;
    }

    public function riskCategory(): RiskLevelCategoryEnum
    {
        return RiskLevelCategoryEnum::fromScore($this->score());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
