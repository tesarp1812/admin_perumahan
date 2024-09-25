<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Integer;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'penghuni_id',
        'Tanggal_Pembayaran'
    ];

    public function details()
    { 
        return $this->hasMany(DetailPembayaran::class, 'pembayaran_id', 'id');
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['json_id'] = $this->json_id; // Add the custom attribute
        return $array;
    }
}
