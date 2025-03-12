<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ParsGreen
{
    protected string $apiKey;
    protected string $smsNumber;

    public function __construct()
    {
        $this->apiKey = env('PARSGREEN_API_KEY');
        $this->smsNumber = env('PARSGREEN_SENDER');
    }

    private function sendRequest($url, $body)
    {
        try {
            $response = Http::withHeaders([
                'authorization' => 'BASIC APIKEY:' . $this->apiKey,
                'Content-Type' => 'application/json;charset=utf-8',
            ])->post($url, $body);

            return $response->json();
        } catch (\Exception $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function sendSms($mobile, $txt)
    {
        $url = 'https://sms.parsgreen.ir/Apiv2/Message/SendSms';

        $body = [
            'SmsBody' => $txt,
            'Mobiles' => [$mobile],
            'SmsNumber' => $this->smsNumber
        ];

        return $this->sendRequest($url, $body);
    }

    public function sendOtp($mobile, $otp)
    {
        $sms = "کد تأیید شما: $otp";
        return $this->sendSms($mobile, $sms);
    }

    public function sendPassword($mobile, $user_name, $pass)
    {
        $sms = "آی تک، خوش آمدید،\n";
        $sms .= "نام کاربری: $user_name\n";
        $sms .= "کلمه عبور: $pass";

        return $this->sendSms($mobile, $sms);
    }

    public function sendResetPassword($mobile, $user_name, $pass)
    {
        $sms = "آی تک،\n";
        $sms .= "نام کاربری: $user_name\n";
        $sms .= "کلمه عبور جدید: $pass";

        return $this->sendSms($mobile, $sms);
    }
}
