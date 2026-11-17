<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    protected $fillable = ['waste_type_id', 'image_path', 'confidence', 'xp_earned', 'image_hash'];

    public function wasteType()
    {
        return $this->belongsTo(WasteType::class);
    }
}
