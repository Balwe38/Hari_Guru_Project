<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcoProfile extends Model
{
    protected $fillable = ['xp', 'level'];

    /** Profil tunggal aplikasi; dibuat otomatis kalau belum ada. */
    public static function current(): self
    {
        return static::query()->first() ?? static::create(['xp' => 0, 'level' => 1]);
    }
}
