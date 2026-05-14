<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name'
    ];

    /**
     * Relasi: 1 Role memiliki banyak User
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}