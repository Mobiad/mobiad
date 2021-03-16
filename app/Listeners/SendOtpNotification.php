<?php

namespace App\Listeners;

use Irabu\NextSmsGateway\NextSmsGateway;
use App\Events\CustomerRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOtpNotification
{
    /**
     * Handle the event.
     *
     * @param  CustomerRegistered  $event
     * @return void
     */
    public function handle(CustomerRegistered $event)
    {
        $phone = $event->customer->phone;
        $code = $event->otp->token;

        $message = env('NEXTSMS_SENDER_ID') . " OTP access code " . $code . "\n\nPlease be aware, This access code expires in the next 15 minutes";

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
