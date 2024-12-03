<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profile';
    protected $fillable = [
        'nama_usaha', 
        'logo', 
        'banner', 
        'warna', 
        'jam_buka', 
        'jam_tutup',
    ];

    protected $casts = [
        'jam_buka' => 'datetime:H:i',
        'jam_tutup' => 'datetime:H:i',
    ];
    protected $attributes = [
        'warna' => 'default-color',  
    ];
}
