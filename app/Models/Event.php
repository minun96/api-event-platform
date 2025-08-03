<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'artist',
        'event_date',
        'city',
        'price',
        'tickets_number',
    ];
}
