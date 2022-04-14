<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsesTransaksi extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'deskripsi',
    ];
    protected $table = 'proses_transaksi';
    protected $primaryKey='id';
}
