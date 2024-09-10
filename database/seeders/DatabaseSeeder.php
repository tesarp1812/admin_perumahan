<?php

namespace Database\Seeders;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 10; $i++) {
            $penghuniId = Str::uuid();
            $statusPenghuni = $faker->boolean ? 'Tetap' : 'Kontrak';
            $statusMenikah = $faker->boolean ? 'Ya' : 'Tidak';
            DB::table('penghuni')->insert([
                'id' => $penghuniId,
                'Nama_Lengkap' => $faker->name,
                'Status_Penghuni' => $statusPenghuni,
                'Nomor_Telepon' => $faker->phoneNumber,
                'Status_Menikah' =>$statusMenikah,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
