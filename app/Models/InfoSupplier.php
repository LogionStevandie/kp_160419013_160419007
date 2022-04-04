<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoSupplier extends Model
{
    use HasFactory;
    protected $table = 'infoSupplier';
    protected $primaryKey='InfoSupplierID';

    protected $fillable=[
        'name',
        'keterangan',
    ];
}
