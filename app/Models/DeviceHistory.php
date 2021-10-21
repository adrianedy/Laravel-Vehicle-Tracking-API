<?php

namespace App\Models;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\BSON\UTCDateTime;

class DeviceHistory extends Model
{
    protected $connection = 'mongodb';

    public $timestamps = false;

    protected $dates = ['timestamp'];

    protected $fillable = ['lat', 'lng', 'heading'];

    protected $casts = [
        'lat' => 'double',
        'lng' => 'double',
        'heading' => 'double',
    ];

    public function scopeGetCollection($q, $collection)
    {
        $this->collection = $collection;

        return $this;
    }

    public function setLatAttribute($value)
    {
        $this->attributes['lat'] = (double) $value;
    }

    public function setLngAttribute($value)
    {
        $this->attributes['lng'] = (double) $value;
    }

    public function setHeadingAttribute($value)
    {
        $this->attributes['heading'] = (double) $value;
    }

    public function scopeWhereTimestampDate($query, Carbon $date)
    {
        $query->where('timestamp', '>=', new UTCDateTime($date->startOfDay()))
            ->where('timestamp', '<=', new UTCDateTime($date->endOfDay()));
    }
}
