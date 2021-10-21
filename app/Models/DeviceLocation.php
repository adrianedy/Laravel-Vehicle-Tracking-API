<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceLocation extends Model
{
    use SoftDeletes;

    protected $fillable = ['lat', 'lng', 'heading'];

    protected $casts = [
        'lat' => 'double',
        'lng' => 'double',
        'heading' => 'double',
    ];
}
