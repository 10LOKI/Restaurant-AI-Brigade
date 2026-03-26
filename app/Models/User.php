<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ['name', 'email', 'password', 'role', 'dietary_tags'];

    protected $hidden = ['password', 'remember_token'];

    protected $attributes = ['role' => 'client',
    'dietary_tags' => '[]',
    ];
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'dietary_tags'      => 'array',
        ];
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
