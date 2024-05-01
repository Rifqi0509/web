<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vip extends Model
{
    use HasFactory;
    protected $table = 'vip';
    protected $fillable = [
        'kd_undangan',
        'nama',
        'alamat',
        'keperluan',
        'asal_instansi',
        'no_hp',
        'tanggal',
        'departemen',
        'seksi',
        'status',
        'ket',
    ];
}
