<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class RedxService
{
    public function getToken()
    {
        $tokenFile = config('courier.redx.token_file');

        // Create directory if it doesn't exist
        $directory = dirname($tokenFile);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Check if token file exists and is valid
        if (file_exists($tokenFile)) {
            $token = file_get_contents($tokenFile);
            if (!empty($token)) {
                return $token;
            }
        }

        $ch = curl_init('https://api.redx.com.bd/v4/auth/login');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode([
                'phone' => '88' . config('courier.redx.phone'),
                'password' => config('courier.redx.password')
            ])
        ]);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (empty($res['data']['accessToken'])) {
            return null;
        }

        file_put_contents($tokenFile, $res['data']['accessToken']);
        return $res['data']['accessToken'];
    }

    public function check($phone)
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => 0, 'cancel' => 0, 'total' => 0];
        }

        $ch = curl_init("https://redx.com.bd/api/redx_se/admin/parcel/customer-success-return-rate?phoneNumber=88$phone");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                "Accept: application/json"
            ]
        ]);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $success = $res['data']['deliveredParcels'] ?? 0;
        $total = $res['data']['totalParcels'] ?? 0;

        return [
            'success' => $success,
            'cancel' => $total - $success,
            'total' => $total
        ];
    }
}
