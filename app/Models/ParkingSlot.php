<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingSlot extends Model
{
    use HasFactory;

    protected $fillable = ['area_id', 'slot_number', 'status', 'last_updated'];

    public function area()
    {
        return $this->belongsTo(ParkingArea::class, 'area_id');
    }


    /**
     * Slot memiliki satu sensor
     */
    public function sensor(): HasOne
    {
        return $this->hasOne(Sensor::class);
    }

    /**
     * Slot memiliki banyak transaksi parkir
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(ParkingTransaction::class);
    }
}
