<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BladeViewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('blade_views')->insert([
            [
                'key' => 'hero',
                'content' => '<div class="hero"><h1>{{ $title }}</h1><p>{{ $description }}</p><a href="{{ $url }}">{{ $button }}</a></div>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
