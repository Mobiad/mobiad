<?php

namespace Irabu\NextSmsGateway;

use Illuminate\Support\Facades\Config;

class NEXTSMSGatewayException extends \Exception
{
}

class NextSmsGateway
{
    protected $_senderid;
    protected $_authKey;

    protected $_requestBody;
    protected $_requestUrl;

    protected $_responseBody;
    protected $_responseInfo;

    const SMS_URL          = 'https://www.nextsms.co.tz/api/sms/v1/text/single';


    //Turn this on if you run into problems. It will print the raw HTTP response from our server
    const Debug            = false;

    const HTTP_CODE_OK      = 200;
    const HTTP_CODE_CREATED = 201;

    public function __construct()
    {
        $this->_senderid    = Config::get('sender_id');
        $this->_authKey      = Config::get('auth_key');

        $this->_requestBody = null;
        $this->_requestUrl  = null;

        $this->_responseBody = null;
        $this->_responseInfo = null;
    }
    // Messaging methods
    public function sendMessage($to_, $message_)
    {
        if (strlen($to_) == 0 || strlen($message_) == 0) {
            throw new NEXTSMSGatewayException('Please supply both to and message parameters');
        }

        $params = array(
            'from' => $this->_senderid,
            'to'       => $to_,
            'message'  => $message_,
        );

        $this->_requestUrl  = self::SMS_URL;
        // $this->_requestBody = http_build_query($params, '', '&');
        $this->_requestBody =json_encode($params);

        $this->executePOST();

        if ($this->_responseInfo['http_code'] == self::HTTP_CODE_CREATED) {
            $responseObject = json_decode($this->_responseBody);
            return $responseObject->SMSMessageData->Recipients;
        }

        throw new NEXTSMSGatewayException($this->_responseBody);
    }

    private function executePost()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_requestBody);
        curl_setopt($ch, CURLOPT_POST, 1);
        $this->doExecute($ch);
    }
    private function executeGet()
    {
        $ch = curl_init();
        $this->doExecute($ch);
    }

    private function doExecute(&$curlHandle_)
    {
        try {

            $this->setCurlOpts($curlHandle_);
            $responseBody = curl_exec($curlHandle_);

            if (self::Debug) {
                echo "Full response: " . print_r($responseBody, true) . "\n";
            }

            $this->_responseInfo = curl_getinfo($curlHandle_);

            $this->_responseBody = $responseBody;
            curl_close($curlHandle_);
        } catch (Exeption $e) {
            curl_close($curlHandle_);
            throw $e;
        }
    }

    private function setCurlOpts(&$curlHandle_)
    {
        dd($this->_authKey);
        curl_setopt($curlHandle_, CURLOPT_TIMEOUT, 60);
        curl_setopt($curlHandle_, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curlHandle_, CURLOPT_URL, $this->_requestUrl);
        curl_setopt($curlHandle_, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle_, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization:' . $this->_authKey
        ));
    }
}
