<?php

namespace App\Listeners;

use App\Events\CustomerVerified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeNotification
{

    /**
     * Handle the event.
     *
     * @param  CustomerVerified  $event
     * @return void
     */
    public function handle(CustomerVerified $event)
    {
        $phone = $event->customer->phone;
        $message = trans('customer.welcome');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.nextsms.co.tz/api/sms/v1/text/single",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode(array("from" => "Mobiad", "to" => $phone, "text" => $message)),
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . env('NEXTSMS_AUTH_KEY'),
                "Content-Type: application/json",
                "Accept: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
    }
}
