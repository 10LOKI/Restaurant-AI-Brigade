<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $fillable = ['user_id', 'plate_id', 'score', 'label', 'warning_message', 'conflicting_tags', 'status'];

    protected $casts = ['conflicting_tags' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plate()
    {
        return $this->belongsTo(Plate::class);
    }
}
