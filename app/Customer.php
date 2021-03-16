<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'fullname', 'phone', 'businessname', 'businessdesc', 'subscription_period', 'subscriber_numbers', 'terms_and_conditions', 'phone_verified_at', 'verified', 'tune', 'voice', 'ref'
    ];

    protected $casts = [
        'subscriber_numbers' => 'array',
    ];
}
