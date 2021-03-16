<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'fullname',
        'phone',
        'businessname',
        'businessdesc',
        'subscription_period',
        'subscriber_numbers',
        'terms_and_conditions',
        'phone_verified_at',
        'verified',
        'tune',
        'voice',
        'ref',
        'is_paid',

        'monthly_fee',
        'subscription_reference',
        'receipt_number',
        'subscription_start',
        'subscription_end'
    ];

    protected $casts = [
        'subscriber_numbers' => 'array',
    ];

    public function phones(){
        return $this->hasMany('App\SubscriberPhone','customer_id');
    }

}
