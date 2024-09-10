<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    protected $table = 'penghuni';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'Nama_Lengkap',
        'Foto_KTP',
        'Status_Penghuni',
        'Nomor_Telepon',
        'Status_Menikah'
    ];


}
