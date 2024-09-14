<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        // Insert warga
        $wargaIds = [];
        for ($i = 0; $i < 30; $i++) {
            $wargaId = Str::uuid();
            $wargaIds[] = $wargaId; // Save ID for later use
            $statusMenikah = $faker->boolean ? 'Ya' : 'Tidak';

            DB::table('warga')->insert([
                'id' => $wargaId,
                'nama' => $faker->name,
                'Foto_KTP' => 'test',
                'Nomor_Telepon' => $faker->phoneNumber,
                'Status_Menikah' => $statusMenikah,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert rumah
        $rumahIds = [];
        $totalRumah = 20;
        for ($i = 0; $i < $totalRumah; $i++) {
            $rumahID = Str::uuid();
            $rumahIds[] = $rumahID; // Save ID for later use

            DB::table('rumah')->insert([
                'id' => $rumahID,
                'no_rumah' => ($i + 1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert penghuni
        $statusPenghuni = array_merge(
            array_fill(0, 15, 'Tetap'), // 15 "Tetap" status
            array_fill(0, 5, 'Kontrak') // 5 "Kontrak" status
        );
        shuffle($statusPenghuni); // Shuffle statuses to distribute them randomly

        foreach ($rumahIds as $rumahId) {
            $numPenghuni = $faker->numberBetween(2, 5); // Minimum 2 penghuni per rumah, maximum 5

            for ($j = 0; $j < $numPenghuni; $j++) {
                // Check if we have enough statusPenghuni left
                if (count($statusPenghuni) === 0) {
                    break;
                }

                // Take one status from the shuffled array
                $status = array_shift($statusPenghuni);

                // Get random warga
                $wargaId = $faker->randomElement($wargaIds);
                $penghuniId = Str::uuid();

                // Set end_date based on status
                $endDate = $status === 'Tetap' ? null : $faker->dateTimeBetween('+1 week', '+20 weeks')->format('Y-m-d H:i:s');

                DB::table('penghuni')->insert([
                    'id' => $penghuniId,
                    'warga_id' => $wargaId,
                    'rumah_id' => $rumahId,
                    'Status_Penghuni' => $status,
                    'start_date' => $faker->dateTimeBetween('-20 weeks', '-15 weeks')->format('Y-m-d H:i:s'),
                    'end_date' => $endDate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }


        // Insert iuran
        DB::table('iuran')->insert([
            'id' => (string) Str::uuid(),
            'nama_iuran' => "Iuran Satpam",
            'harga' => 100000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('iuran')->insert([
            'id' => (string) Str::uuid(),
            'nama_iuran' => "Iuran Kebersihan",
            'harga' => 15000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
