<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Device extends Model
{
    use SoftDeletes;
    
    protected $primaryKey = 'device_id';
    
    public $incrementing = false;
    
    protected $fillable = ['device_id', 'name', 'license', 'type'];

    protected $casts = [
        'device_id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->type = $query->type ?? 0;
        });
    }

    public function getTypeAttribute($value)
    {
        return $value != 0 ? (double) $value : null;
    }

    public function getTextTypeAttribute($value)
    {
        switch ($value) {
            case 2:
                return "2 wheeler";
                break;
            case 4:
                return "4 wheeler";
                break;
            default:
                return null;
        }
    }

    public function location()
    {
        return $this->hasOne(DeviceLocation::class, 'device_id', 'id');
    }

    public static function generateDeviceId($is_official = false)
    {
        $year      = date('Y');
        $prefix    = "D" . $year;
        $prefixLen = strlen($prefix);
        $lastId    = DB::table('devices')->select("device_id")
            ->whereRaw("SUBSTRING(device_id, " . ($prefixLen - 3) . ", 4) = ?", [date('Y')])
            ->orderByRaw("SUBSTRING(device_id, " . ($prefixLen + 1) . ", LENGTH(device_id) - {$prefixLen}) + 0 DESC");

        $lastId = $lastId->first()->device_id ?? null;

        if ($lastId) {
            $increment = substr($lastId, 5);
            $increment = (int) $increment + 1;

            return $prefix . str_pad($increment, 4, '0', STR_PAD_LEFT);
        }

        return $prefix . '0001';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
