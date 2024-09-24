<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan'; // Set primary key ke 'id_layanan'

    public $incrementing = true; // Jika 'id_layanan' auto-increment, pastikan true
    
    protected $keyType = 'int'; // Jika 'id_layanan' adalah integer

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'gambar',
        'waktu',
    ];
}
