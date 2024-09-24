<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'booking';
    protected $primaryKey = 'id_booking'; // Set primary key ke 'id_layanan'

    public $incrementing = true; // Jika 'id_layanan' auto-increment, pastikan true
    
    protected $keyType = 'int'; // Jika 'id_layanan' adalah integer

}
