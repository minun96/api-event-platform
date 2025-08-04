<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{
    protected $fillable = [
        'event_id',
        'quantity',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
