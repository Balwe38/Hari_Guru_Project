<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    // Scan.php
protected $fillable = ['waste_type_id', 'image_path', 'confidence'];
public function wasteType() { return $this->belongsTo(WasteType::class); }
}
