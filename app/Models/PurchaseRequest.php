<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $table = 'purchase_request';

    protected $fillable=[
        'name',
        'totalHarga',
        'idLokasi',
        'idGudang',
        'tanggalDiterima',
        'created_by',
        'created_on',
        'updated_by',
        'updated_on',
        'approved',
    ];
}
