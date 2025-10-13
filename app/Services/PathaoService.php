<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PathaoService
{
    public function getToken()
    {
        $tokenFile = config('courier.pathao.token_file');

        // Create directory if it doesn't exist
        $directory = dirname($tokenFile);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Check if token file exists and is valid
        if (file_exists($tokenFile)) {
            $cached = json_decode(file_get_contents($tokenFile), true);
            if ($cached && isset($cached['access_token']) && time() < $cached['expires_at']) {
                return $cached['access_token'];
            }
        }

        $ch = curl_init('https://api-hermes.pathao.com/aladdin/api/v1/issue-token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode([
                'client_id' => config('courier.pathao.client_id'),
                'client_secret' => config('courier.pathao.client_secret'),
                'grant_type' => 'password',
                'username' => config('courier.pathao.username'),
                'password' => config('courier.pathao.password')
            ]),
        ]);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (empty($res['access_token'])) {
            return null;
        }

        $expires_in = $res['expires_in'] ?? 3600;
        file_put_contents($tokenFile, json_encode([
            'access_token' => $res['access_token'],
            'expires_at' => time() + $expires_in - 60
        ]));

        return $res['access_token'];
    }

    public function check($phone)
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => 0, 'cancel' => 0, 'total' => 0];
        }

        $ch = curl_init('https://merchant.pathao.com/api/v1/user/success');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $token",
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode(['phone' => $phone])
        ]);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $success = $res['data']['customer']['successful_delivery'] ?? 0;
        $total = $res['data']['customer']['total_delivery'] ?? 0;

        return [
            'success' => $success,
            'cancel' => $total - $success,
            'total' => $total
        ];
    }
}
