<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ParsGreenService
{
    protected string $apiKey = '479E7C62-E901-4952-8017-A99604BBB69E';
    protected string $smsNumber = '10001983';


    // ارسال پیامک عمومی
    public function sendSms($mobile, $txt)
    {
        $url = 'https://sms.parsgreen.ir/Apiv2/Message/SendSms';

        $body = [
            'SmsBody' => $txt,
            'Mobiles' => [$mobile],
            'SmsNumber' => $this->smsNumber
        ];

        $response = Http::withHeaders([
            'authorization' => 'BASIC APIKEY:' . $this->apiKey,
            'Content-Type' => 'application/json;charset=utf-8',
        ])->post($url, $body);

        return $response->successful();
    }

    // ارسال کد OTP
    public function sendOtp($mobile, $otp)
    {
        $sms = 'آموزشگاه آی تک، کد یکبارمصرف: ' . $otp;
        return $this->sendSms($mobile, $sms);
    }

    // ارسال پیامک تغییر پسورد
    public function sendPassword($mobile, $user_name, $pass)
    {
        $sms = 'آی تک، خوش آمدید،' . "\n";
        $sms .= 'نام کاربری: ' . $user_name . "\n";
        $sms .= 'کلمه عبور: ' . $pass;
        return $this->sendSms($mobile, $sms);
    }

    // ارسال پیامک بازنشانی پسورد
    public function sendResetPassword($mobile, $user_name, $pass)
    {
        $sms = 'آی تک،' . "\n";
        $sms .= 'نام کاربری: ' . $user_name . "\n";
        $sms .= 'کلمه عبور جدید: ' . $pass;
        return $this->sendSms($mobile, $sms);
    }

}
