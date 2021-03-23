<?php

namespace App\Http\Controllers;

use App\GoodObject;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class NotificationController extends Controller{


    public static function sendSms($phoneNumber, $message){

        $authKey = Config::get('nextsms.sender_id');
        $senderId = Config::get('nextsms.auth_key');
        $url = Config::get('nextsms.single_url');

        $api_key = 'Basic ' . $authKey;

        $payload = new GoodObject();
        $payload->from = $senderId;
        $payload->to = $phoneNumber;
        $payload->text = $message;

        $body = json_encode($payload);

        $client = new Client(['headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $api_key]
        ]);

        $response = $client->post($url, ['body' => $body]);
        return json_encode($response);
    }


}


