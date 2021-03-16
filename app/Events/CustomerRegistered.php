<?php

namespace App\Events;

use App\Customer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;
	public $otp;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($otp, Customer $customer)
    {
        $this->otp = $otp;
		$this->customer = $customer;
    }
}
