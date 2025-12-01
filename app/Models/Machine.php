<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Machine extends Model
{
    use HasFactory;

    // Izinkan mass assignment
    protected $fillable = ['plant_id', 'name'];

    /**
     * Relasi: Satu Machine MILIK satu Plant
     */
    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}
