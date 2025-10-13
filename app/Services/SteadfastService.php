<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SteadfastService
{
    public function check($phone)
    {
        $cookieFile = public_path('cache/steadfast_cookie.txt');
        $ch = curl_init('https://steadfast.com.bd/login');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_COOKIEJAR => $cookieFile,
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);
        $body = substr(curl_exec($ch), curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        if (!preg_match('/name="_token" value="([^"]+)"/', $body, $m) &&
            !preg_match('/<meta name="csrf-token" content="([^"]+)"/i', $body, $m)) {
            return ['success' => 0, 'cancel' => 0, 'total' => 0];
        }
        $token = $m[1];

        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://steadfast.com.bd/login',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                '_token' => $token,
                'email' => config('courier.steadfast.email'),
                'password' => config('courier.steadfast.password')
            ]),
            CURLOPT_HEADER => false
        ]);
        curl_exec($ch);

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://steadfast.com.bd/user/frauds/check/$phone",
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POST => false
        ]);
        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return [
            'success' => $data['total_delivered'] ?? 0,
            'cancel' => $data['total_cancelled'] ?? 0,
            'total' => ($data['total_delivered'] ?? 0) + ($data['total_cancelled'] ?? 0)
        ];
    }
}
