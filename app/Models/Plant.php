<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plant extends Model
{
    use HasFactory;

    // Izinkan mass assignment untuk kolom 'name'
    protected $fillable = ['name'];

    /**
     * Relasi: Satu Plant punya BANYAK Machine
     */
    public function machines()
    {
        // Pastikan class Machine diimport atau dipanggil dengan benar
        return $this->hasMany(Machine::class);
    }
}
