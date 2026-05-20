<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'raw_materials';

    protected $fillable = [
        'name',
        'unit',
        'stock',
        'min_stock'
    ];
}
