<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'icon',
        'metric', 'target', 'xp_reward', 'unlocked_at',
    ];

    protected function casts(): array
    {
        return ['unlocked_at' => 'datetime'];
    }

    public function isUnlocked(): bool
    {
        return $this->unlocked_at !== null;
    }
}
