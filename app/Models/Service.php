<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;


    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'gambar',
        'waktu'
    ];
}
