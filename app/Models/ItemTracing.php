<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTracing extends Model
{
    use HasFactory;
     protected $table = 'itemTracing';
    protected $primaryKey='itemTracingID';
}
