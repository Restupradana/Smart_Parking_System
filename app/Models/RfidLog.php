<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfidLog extends Model
{
    use HasFactory;

    protected $table = 'rfid_logs';

    protected $fillable = [
        'user_id',
        'rfid_card_id',
        'slot_id',
        'action',
        'location',
        'gate',
        'logged_at',
    ];

    public $timestamps = true;

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RFID Card
    public function rfidCard()
    {
        return $this->belongsTo(RfidCard::class, 'rfid_card_id');
    }

    // Slot Parkir
    public function slot()
    {
        return $this->belongsTo(ParkingSlot::class, 'slot_id');
    }
}
