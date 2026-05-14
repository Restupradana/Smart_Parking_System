<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingTransaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'slot_id',
        'entry_time',
        'exit_time',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function slot()
    {
        return $this->belongsTo(ParkingSlot::class);
    }
}
