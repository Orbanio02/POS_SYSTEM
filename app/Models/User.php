<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* ============================
       Product access (CLIENT ONLY)
    ============================ */
    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('assigned_by')
            ->withTimestamps();
    }
}
