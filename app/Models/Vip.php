<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vip extends Model
{
    use HasFactory;
    protected $table = 'vip';
    protected $fillable = [
        'nama',
        'alamat',
        'keperluan',
        'asal_instansi',
        'no_hp',
        'tanggal',
        'jam',
        'status',
        'departemen',
        'seksi',
        'ket',
    ];
}