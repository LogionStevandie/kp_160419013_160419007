<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class COA extends Model
{
    use HasFactory;
     protected $table = 'COA';
     protected $primaryKey='COAID';
      protected $fillable=[
        'COAID',
        'Nomor',
        'Nama',
        'Chead',
        'Cdet'
    ];
}
