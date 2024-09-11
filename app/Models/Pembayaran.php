<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'Nama_Lengkap',
        'Foto_KTP',
        'Status_Pembayaran',
        'Nomor_Telepon',
        'Status_Menikah'
    ];


}
