<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionGudangBarang extends Model
{
    use HasFactory;
    protected $table = 'transaction_gudang_barang';
    protected $primaryKey='id';
}
