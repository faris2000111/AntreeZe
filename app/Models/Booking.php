<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';
    protected $primaryKey = 'id_booking'; // Set primary key ke 'id_booking'
    public $incrementing = true; // Jika 'id_booking' auto-increment
    protected $keyType = 'int'; // Jika 'id_booking' adalah integer

    protected $fillable = [
        // Daftar field yang dapat diisi di sini
        'id_pelayanan', // Pastikan ini adalah foreign key ke tabel pelayanan
        'tanggal',
        'status',
        'catatan',
    ];

    // Tambahkan relasi ke Pelayanan
    public function pelayanan()
    {
        return $this->belongsTo(Pelayanan::class, 'no_pelayanan', 'no_pelayanan'); 
    }
}
