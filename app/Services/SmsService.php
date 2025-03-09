<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected string $apiKey = '93A4A986-5501-40ED-BBB8-92D6744B48E0';
    protected string $smsNumber = '10001983';
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
