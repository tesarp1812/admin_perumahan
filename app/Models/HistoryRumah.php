<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryRumah extends Model
{
    protected $table = 'history_penghuni_rumah';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'rumah_id',
        'penghuni_id',
        'Tanggal_Mulai',
        'Tanggal_Selesai'
    ];
}
