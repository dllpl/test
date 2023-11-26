<?php

namespace App\Http\Controllers;

class WhatsAppController extends Controller
{

    public static function send($phone, $message)
    {
        $token = env('WA_TOKEN', 'YbUz0Z3mQnwMDd055a356297b3008a5cde8d204b079e6');
        $base_url = env('WA_BASE_URL', 'https://app.api-messenger.com/sendmessage?token=');

        $array = [
            [
                'chatId' => self::format_phone($phone) . "@c.us",
                'message' => $message,
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url . $token);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result, true);
        return $result;
    }

    private static function format_phone($phone)
    {
        $phonewa = trim($phone);
        $res = preg_replace(
            array(
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',
            ),
            array(
                '7$2$3$4$5',
                '7$2$3$4$5',
                '7$2$3$4$5',
                '7$2$3$4$5',
                '7$2$3$4',
                '7$2$3$4',
            ),
            $phonewa
        );
        return $res;
    }
}