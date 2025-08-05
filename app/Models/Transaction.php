<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'book_id',
        'tanggal_peminjaman',
        'tanggal_jatuh_tempo',
        'tanggal_pengembalian',
        'denda',
        'status',
    ];

    protected $casts = [
        'tanggal_peminjaman' => 'datetime',
        'tanggal_jatuh_tempo' => 'datetime',
        'tanggal_pengembalian' => 'datetime',
        'denda' => 'integer',
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke model Book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
