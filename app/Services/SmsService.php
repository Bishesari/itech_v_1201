<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected string $apiKey;
    protected string $smsNumber;
    public function __construct()
    {
        $this->apiKey = env('PARSGREEN_API_KEY');
        $this->smsNumber = env('PARSGREEN_SMS_NUMBER');
    }
    protected string $apiUrl = 'https://sms.parsgreen.ir/Apiv2/Message/SendSms';

    public function sendOtp($mobile, $otp)
    {
        return $this->sendSms($mobile, "کد تأیید شما: $otp");
    }

    public function sendSms($mobile, $message)
    {
        $response = Http::post($this->apiUrl, [
            'SmsBody'  => $message,
            'Mobiles'  => [$mobile],
            'SmsNumber'=> $this->smsNumber,
        ]);

        return $response->json();
    }

}
