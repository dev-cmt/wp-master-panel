<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FrodlyController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/^\d{11}$/'
        ]);

        $phone = $request->input('phone');

        // Call courier methods
        $results = [
            'Redx'       => $this->getRedx($phone),
            'SteadFast'  => $this->getSteadFast($phone),
            'Pathao'     => $this->getPathao($phone),
            'Paperfly'   => $this->getPaperfly($phone),
        ];

        // Build summary
        $summaries = [];
        $total_all = $success_all = $cancel_all = 0;

        foreach ($results as $courier => $data) {
            $summaries[$courier] = [
                'logo'    => config("courier.logos." . strtolower($courier), ''),
                'total'   => $data['total'],
                'success' => $data['success'],
                'cancel'  => $data['cancel']
            ];

            $total_all   += $data['total'];
            $success_all += $data['success'];
            $cancel_all  += $data['cancel'];
        }

        return response()->json([
            'status' => true,
            'message' => 'Courier info retrieved successfully.',
            'data' => [
                'Summaries'   => $summaries,
                'totalSummary'=> [
                    'total'       => $total_all,
                    'success'     => $success_all,
                    'cancel'      => $cancel_all,
                    'successRate' => $total_all ? round(($success_all / $total_all) * 100) : 0,
                    'cancelRate'  => $total_all ? round(($cancel_all / $total_all) * 100) : 0
                ]
            ]
        ]);
    }

    public function checkManualy(Request $request)
    {
        // Get token from header or query
        $token = $request->header('X-API-TOKEN') ?? $request->query('token');

        // Predefined token or phone
        $validToken = '1234567890abcdef';

        if (!$token || $token !== $validToken) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or missing token'
            ], 401);
        }

        // Validate phone
        $request->validate([
            'phone' => 'required|regex:/^\d{11}$/'
        ]);

        $phone = $request->input('phone');

        // Call courier methods
        $results = [
            'Redx'       => $this->getRedx($phone),
            'SteadFast'  => $this->getSteadFast($phone),
            'Pathao'     => $this->getPathao($phone),
            'Paperfly'   => $this->getPaperfly($phone),
        ];

        // Build summary
        $summaries = [];
        $total_all = $success_all = $cancel_all = 0;

        foreach ($results as $courier => $data) {
            $summaries[$courier] = [
                'logo'    => config("courier.logos." . strtolower($courier), ''),
                'total'   => $data['total'],
                'success' => $data['success'],
                'cancel'  => $data['cancel']
            ];

            $total_all   += $data['total'];
            $success_all += $data['success'];
            $cancel_all  += $data['cancel'];
        }

        return response()->json([
            'status' => true,
            'message' => 'Courier info retrieved successfully.',
            'data' => [
                'Summaries'   => $summaries,
                'totalSummary'=> [
                    'total'       => $total_all,
                    'success'     => $success_all,
                    'cancel'      => $cancel_all,
                    'successRate' => $total_all ? round(($success_all / $total_all) * 100) : 0,
                    'cancelRate'  => $total_all ? round(($cancel_all / $total_all) * 100) : 0
                ]
            ]
        ]);

    }

    // ------------------- REDX -------------------
    private function redxLogin(): ?string
    {
        $config = [
            'phone'      => '01648811536',
            'password'   => 'DnBRedx2025$',
            'token_file' => public_path('frodly/redx_token.json'),
            'api_base'   => 'https://api.redx.com.bd/v4',
        ];

        @mkdir(dirname($config['token_file']), 0777, true);

        if (file_exists($config['token_file']) && time() - filemtime($config['token_file']) < 50 * 60) {
            return trim(file_get_contents($config['token_file']));
        }

        $ch = curl_init("{$config['api_base']}/auth/login");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode([
                'phone'    => '88' . $config['phone'],
                'password' => $config['password'],
            ]),
        ]);

        $response = curl_exec($ch);
        $res = json_decode($response, true);
        curl_close($ch);

        if (!isset($res['data']['accessToken'])) {
            return null;
        }

        file_put_contents($config['token_file'], $res['data']['accessToken']);
        return $res['data']['accessToken'];
    }

    private function getRedx(string $phone): array
    {
        // $token = $this->redxLogin();
        // $token = config('frodly.steadfast.token_data');

        $token = 'a47a79745b53b36afb14c782794c7dcdc691d09dd3161969fcfd732f18848b1df4bc87521e1cb9b9e27e528c052b42cbde3f6780abc861d8fdee8b55d8e6e9e6';

        if (!$token) {
            return ['success' => 0, 'cancel' => 0, 'total' => 0];
        }

        $ch = curl_init("https://redx.com.bd/api/redx_se/admin/parcel/customer-success-return-rate?phoneNumber=88$phone");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Authorization: Bearer $token", "Accept: application/json"]
        ]);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $success = $res['data']['deliveredParcels'] ?? 0;
        $total = $res['data']['totalParcels'] ?? 0;

        return [
            'success' => $success,
            'cancel'  => $total - $success,
            'total'   => $total
        ];
    }

    // ------------------- STEADFAST -------------------
    private function steadFastLogin()
    {
        $config = [
            'email'       => 'unisalemart890@gmail.com',
            'password'    => 'Babla2k12@#$%',
            'cookie_file' => public_path('frodly/steadfast_cookie.txt'),
            'base_url'    => 'https://steadfast.com.bd/login',
        ];

        @mkdir(dirname($config['cookie_file']), 0777, true);

        $ch = curl_init($config['base_url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_COOKIEJAR      => $config['cookie_file'],
            CURLOPT_COOKIEFILE     => $config['cookie_file'],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($response, $headerSize);

        if (!preg_match('/name="_token" value="([^"]+)"/', $body, $m) &&
            !preg_match('/<meta name="csrf-token" content="([^"]+)"/i', $body, $m)) {
            curl_close($ch);
            return false;
        }

        $token = $m[1];

        curl_setopt_array($ch, [
            CURLOPT_URL        => $config['base_url'],
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => http_build_query([
                '_token'   => $token,
                'email'    => $config['email'],
                'password' => $config['password']
            ]),
            CURLOPT_HEADER     => false
        ]);

        curl_exec($ch);
        return $ch;
    }

    private function getSteadFast($phone)
    {
        $ch = $this->steadFastLogin();
        if (!$ch) return ['success'=>0,'cancel'=>0,'total'=>0];

        curl_setopt_array($ch, [
            CURLOPT_URL            => "https://steadfast.com.bd/user/frauds/check/$phone",
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POST           => false,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        return [
            'success' => $data['total_delivered'] ?? 0,
            'cancel'  => $data['total_cancelled'] ?? 0,
            'total'   => ($data['total_delivered'] ?? 0) + ($data['total_cancelled'] ?? 0)
        ];
    }

    // ------------------- PATHAO -------------------
    private function pathaoLogin(): ?string
    {
        $config = [
            'email'         => 'stylisfirst@gmail.com',
            'password'      => 'STYLIS@22bd',
            'client_id'     => 'APdRlXYaGy',
            'client_secret' => 'dkWMLtqJbTvOemaWwBwhy6bmDC6zv75AzCbKcqlS',
            'token_cache'   => public_path('frodly/pathao_token.json'),
            'token_url'     => 'https://api-hermes.pathao.com/aladdin/api/v1/issue-token',
        ];

        @mkdir(dirname($config['token_cache']), 0777, true);

        if (file_exists($config['token_cache'])) {
            $cache = json_decode(file_get_contents($config['token_cache']), true);
            if (!empty($cache['access_token']) && !empty($cache['expires_at']) && $cache['expires_at'] > time()) {
                return $cache['access_token'];
            }
        }

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->timeout(15)
            ->post($config['token_url'], [
                'client_id'     => $config['client_id'],
                'client_secret' => $config['client_secret'],
                'grant_type'    => 'password',
                'username'      => $config['email'],
                'password'      => $config['password'],
            ]);

        if (!$response->successful()) {
            Log::error("Pathao token request failed", [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return null;
        }

        $res = $response->json();
        if (empty($res['access_token'])) return null;

        // save with expiry time (3600 sec default)
        file_put_contents($config['token_cache'], json_encode([
            'access_token' => $res['access_token'],
            'expires_at'   => time() + ($res['expires_in'] ?? 3600),
        ]));

        return $res['access_token'];
    }

    private function getPathao(string $phoneNumber): array
    {
        $timeout  = 15;
        $maxTries = 2;
        $baseUrl  = 'https://merchant.pathao.com/api/v1';

        if (!preg_match('/^01[3-9]\d{8}$/', $phoneNumber)) {
            return ['status'=>'error','success'=>0,'cancel'=>0,'total'=>0,'message'=>'Invalid phone number format'];
        }

        for ($i=0;$i<$maxTries;$i++) {
            // $token = $this->pathaoLogin();
            // $token = config('frodly.pathao.token_data');
            $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxMDQyOCIsImp0aSI6IjAxNjMxZjUyZjk2YjU0NzdiMTA3MWM1MjliYjA5MmEwODdjYmViOTYwMjdiMTNhNzk1OTllNTAxMzIwNGY2ZDU3ZjhiYzc4NjVlMDZiMzllIiwiaWF0IjoxNzU3NTA5NDk5LjI4NzUyMiwibmJmIjoxNzU3NTA5NDk5LjI4NzUyNCwiZXhwIjoxNzY1Mjg1NDk5LjI2MDc2OSwic3ViIjoiODYyODciLCJzY29wZXMiOltdfQ.JN-puiH4MIlCHqAquE-aeH73_HKeySLckIMpoW8AiZ2-XgmQm5OKSlcy6RvPvECHyd-amNwzVlV2jmkGgjKtGgOqnDkOXugF9qXuyZE6jUPf8C66wrMm3CIu-Wu3cHJSUOWXQ7N-ZQqX_AcmUjVX_CS0HIvhyQicYoUl_ApCFPKyW5Nnqw6Rzz9FY5Wpoxg_6cUkzkn0TnzJwNumetaLMNcaSDWdJWoH6NYZMxpo-8y5BMBASayI8y7yFaNdVc5MC0O1E6pJNX2tFsBILJIG1_D-aOU6Ubn22MqPJG3Ld5XS8HX_Wv-8h_i9DuHc_LNwaj0pnclMsOizr2DOdTyByx4CVHEnfmw6Z8OuIZN8Qonr6Q3zlysinrxEp88if1OcahuGP36TC5RPx5fO1msaev0MUpVDYmdCs4kCXlpOrycM0lQSzrlY79L9VNkWXi6PRL9jzmlwq29PoQ0B9aPesKt-nxBJ7__Wr49rfEtCWI2hchIXiHKwQAMxasSAsJSLx8RrL4VaRe2i_A2QzuDqgFMvxfxrxqKvXPNq5H_mRC3h9Ct_M_ZouuhHu8xeFQGa-r0W0XjwcfB22hNOdOaXXLol5kS5gwHn6dH6SW93KT-mchGRvryKUY-QWn2E37wOYA-Wrt33G-OGNJgASpeO13Q9Hzg2RSwz4wFWnDHnoSw';


            if (!$token) continue;

            $response = Http::withHeaders([
                'Accept'=>'application/json',
                'Content-Type'=>'application/json',
                'Authorization'=>'Bearer '.$token
            ])
            ->timeout($timeout)
            ->post("{$baseUrl}/user/success", ['phone'=>$phoneNumber]);

            if ($response->successful()) {
                $res = $response->json();
                $success = $res['data']['customer']['successful_delivery'] ?? 0;
                $total   = $res['data']['customer']['total_delivery'] ?? 0;

                return ['success'=>$success,'cancel'=>$total-$success,'total'=>$total];
            }
        }

        return ['success'=>0,'cancel'=>0,'total'=>0,'status'=>'Max retries reached'];
    }

    // ------------------- PAPERFLY -------------------
    private function getPaperfly(string $phoneNumber): array
    {
        return ['success'=>0,'cancel'=>0,'total'=>0,'status'=>'success'];
    }
}
