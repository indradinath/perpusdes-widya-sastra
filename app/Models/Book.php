<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'deskripsi',
        'gambar_sampul',
        'jumlah_stok',
        'jumlah_tersedia',
        'kategori_id',
        'rack_id',
    ];


    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }


    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }
}
