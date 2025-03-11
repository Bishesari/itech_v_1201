<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected string $apiKey = '479E7C62-E901-4952-8017-A99604BBB69E';
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
