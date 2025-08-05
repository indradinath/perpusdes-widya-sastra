<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_pengunjung',
        'keperluan',
        'asal_instansi',
        'waktu_kunjungan',
    ];

    protected $casts = [
        'waktu_kunjungan' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
