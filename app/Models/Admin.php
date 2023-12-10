<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'last_login',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function inspections()
    {
        return $this->hasMany(Inspection::class);

    }
}
