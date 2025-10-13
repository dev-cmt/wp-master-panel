<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Page;

class DefaultSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::create([
            'title'     => 'home',
            'slug'      => 'home',
            'content'   => 'This is the home page content.',
        ]);

        DB::table('stores')->insert([
            'id' => 1,
            'name' => 'Sky Tech Shop',
            'prefix' => 'STS',
            'base_url' => 'https://wp.skytechsolve.com',
            'api_key' => 'ck_0ee025dd3680bd94488a628ad0e146cac66c0f91',
            'api_secret' => 'cs_51da4f4a33b9637b590fbb4ed95ec9664368caea',
            'ep_order_store' => '/wp-json/wc/v3/orders',
            'ep_order_update' => null,
            'ep_order_status' => null,
            'ep_order_delete' => null,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
