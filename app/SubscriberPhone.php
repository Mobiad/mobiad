<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriberPhone extends Model
{
    protected $fillable = [
        'customer_id',
        'phone',
        'otp',
        'has_accepted_terms',
        'is_activated',
    ];
}
