<?php

namespace App\Services;

class PaperflyService
{
    public function check($phone)
    {
        $ch = curl_init('https://api.paperfly.com.bd/v1/merchant/parcel/success');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode(['phone' => $phone])
        ]);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if (empty($res['data'])) {
            return ['success' => 0, 'cancel' => 0, 'total' => 0];
        }

        $data = $res['data'];
        return [
            'success' => $data['successful_delivery'] ?? 0,
            'cancel' => $data['total_delivery'] - ($data['successful_delivery'] ?? 0),
            'total' => $data['total_delivery'] ?? 0
        ];
    }
}
