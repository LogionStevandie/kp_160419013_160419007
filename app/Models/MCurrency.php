<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCurrency extends Model
{
    use HasFactory;
    protected $table = 'MCurrency';
    protected $primaryKey='MCurrencyID';
}
