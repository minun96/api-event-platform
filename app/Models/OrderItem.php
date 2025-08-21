<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'event_id',
        'quantity',
        'price',
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }
}
