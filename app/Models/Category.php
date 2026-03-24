<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'color', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function plates()
    {
        return $this->hasMany(Plate::class);
    }
}
