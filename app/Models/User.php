<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'login', 'password', 'avatar', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['avatar_url'];

    public $timestamps = false;

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isLibrarian()
    {
        return $this->role === 'librarian';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }
}