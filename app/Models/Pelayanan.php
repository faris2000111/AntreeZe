<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelayanan extends Model
{
    use HasFactory;
    protected $table = 'pelayanan';
    protected $primaryKey = 'id_pelayanan'; // Set primary key ke 'id_layanan'

    public $incrementing = true; // Jika 'id_layanan' auto-increment, pastikan true
    
    protected $keyType = 'int'; // Jika 'id_layanan' adalah integer
    protected $fillable = [
        'id_admin',
        'jenis',
        'no_pelayanan',
    ];
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }


}
