<?php

function PM($mobile, $txt): void
{
    $SmsNumber = '10001983';
    $apiKey = '93A4A986-5501-40ED-BBB8-92D6744B48E0';
    $url = 'https://sms.parsgreen.ir/Apiv2/Message/SendSms';
    $ch = curl_init($url);
    //$jsonDataEncoded = '{"SmsBody":"Salam Salam","Mobiles":["09034336111"],"SmsNumber": "10001983"}';
    $str1 = '{"SmsBody":"'.$txt.'","Mobiles":["'.$mobile.'"],"SmsNumber": "'.$SmsNumber.'"}';
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $str1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $header = ['authorization: BASIC APIKEY:'.$apiKey, 'Content-Type: application/json;charset=utf-8'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_exec($ch);
    curl_close($ch);
}

function PM_OTP($mobile, $otp)
{
    $sms = 'آموزشگاه آی تک، کد یکبارمصرف:';
    $sms .= "\n".$otp;
    PM($mobile, $sms);
}

function PM_PASS($mobile, $user_name, $pass)
{
    $sms = 'آی تک، خوش آمدید،'."\n";
    $sms .= 'نام کاربری: '.$user_name."\n";
    $sms .= 'کلمه عبور: '.$pass;
    PM($mobile, $sms);
}

function PM_RESET_PASS($mobile, $user_name, $pass): void
{
    $sms = 'آی تک،'."\n";
    $sms .= 'نام کاربری: '.$user_name."\n";
    $sms .= 'کلمه عبور جدید: '.$pass;
    PM($mobile, $sms);
}
