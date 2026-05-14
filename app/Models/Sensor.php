<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = [
        'slot_id',
        'type',
        'last_status',
        'last_read_at'

    ];

    public function slot()
    {
        return $this->belongsTo(ParkingSlot::class);
    }
}
