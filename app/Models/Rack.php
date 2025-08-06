<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rack extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_rak',
        'nama_rak',
        'lokasi',
    ];
}
