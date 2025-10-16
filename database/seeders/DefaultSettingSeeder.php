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
            'name' => 'Pro Devs Shop',
            'prefix' => 'PDS',
            'base_url' => 'https://wp.prodevsltd.xyz',
            'api_key' => 'ck_4c54d08a7fc40a03d0e4164034d420c486035576',
            'api_secret' => 'cs_849c8e98cf60ca88dbfcd042847eba8c22616ba2',
            'custom_secret' => '.P}Gvo^!O<_f`V9+SYB+I/v/c;Pp@v3w6jG_7%Mr[T+aF6d)N^',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('stores')->insert([
            'id' => 2,
            'name' => 'Sky Tech Shop',
            'prefix' => 'STS',
            'base_url' => 'https://wp.skytechsolve.com',
            'api_key' => 'ck_0ee025dd3680bd94488a628ad0e146cac66c0f91',
            'api_secret' => 'cs_51da4f4a33b9637b590fbb4ed95ec9664368caea',
            'custom_secret' => '.P}Gvo^!O<_f`V9+SYB+I/v/c;Pp@v3w6jG_7%Mr[T+aF6d)N^',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


    }
}
