<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'wa_number',
        'role_id',
    ];

    /**
     * Hidden field
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // =====================================================
    // SINGLE ROLE
    // =====================================================

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    // =====================================================
    // COMPATIBILITY MULTI ROLE
    // AGAR TIDAK ERROR roles()
    // =====================================================

    public function roles()
    {
        return collect([$this->role])->filter();
    }

    // =====================================================
    // RFID UTAMA
    // =====================================================

    public function rfidCard(): HasOne
    {
        return $this->hasOne(RfidCard::class);
    }

    // =====================================================
    // SEMUA RFID
    // =====================================================

    public function rfidCards(): HasMany
    {
        return $this->hasMany(RfidCard::class);
    }

    // =====================================================
    // RFID LOG
    // =====================================================

    public function rfidLogs(): HasMany
    {
        return $this->hasMany(RfidLog::class);
    }

    // =====================================================
    // TRANSAKSI PARKIR
    // =====================================================

    public function parkingTransactions(): HasMany
    {
        return $this->hasMany(ParkingTransaction::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    // =====================================================
    // CEK ROLE
    // =====================================================

    public function hasRole(string $roleName): bool
    {
        return $this->role &&
               strtolower($this->role->name) === strtolower($roleName);
    }

    // =====================================================
    // STATUS PARKIR
    // =====================================================

    public function isParking(): bool
    {
        $lastLog = $this->rfidLogs()
            ->latest('logged_at')
            ->first();

        return $lastLog &&
               $lastLog->action === 'masuk';
    }
}
