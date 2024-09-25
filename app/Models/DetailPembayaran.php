<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembayaran extends Model
{
    protected $table = 'detail_pembayaran';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'pembayaran_id',
        'iuran_id',
        'Tahun'
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id', 'id');
    }

}
