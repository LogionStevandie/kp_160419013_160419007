<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menu';
     protected $primaryKey='MenuID';
     protected $fillable=[
        'Name',
        'Url',
        'Deskripsi'
    ];
}
