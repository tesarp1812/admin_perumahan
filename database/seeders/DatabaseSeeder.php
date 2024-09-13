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

        // Arrays to store penghuni IDs
        $tetapPenghuniIds = [];
        $kontrakPenghuniIds = [];

        // Insert penghuni
        for ($i = 0; $i < 30; $i++) {
            $penghuniId = Str::uuid();
            $statusPenghuni = $faker->boolean ? 'Tetap' : 'Kontrak';
            $statusMenikah = $faker->boolean ? 'Ya' : 'Tidak';

            DB::table('penghuni')->insert([
                'id' => $penghuniId,
                'Nama_Lengkap' => $faker->name,
                'Status_Penghuni' => $statusPenghuni,
                'Nomor_Telepon' => $faker->phoneNumber,
                'Status_Menikah' => $statusMenikah,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Collect IDs based on status
            if ($statusPenghuni === 'Tetap') {
                $tetapPenghuniIds[] = $penghuniId;
            } elseif ($statusPenghuni === 'Kontrak') {
                $kontrakPenghuniIds[] = $penghuniId;
            }
        }

        // Check if we have enough penghuni with 'Tetap' status
        if (count($tetapPenghuniIds) === 0) {
            throw new \Exception('No penghuni with status "Tetap" found.');
        }

        // Check if we have enough penghuni with 'Kontrak' status
        if (count($kontrakPenghuniIds) === 0) {
            throw new \Exception('No penghuni with status "Kontrak" found.');
        }

        // Insert rumah with penghuni having 'Tetap' status
        $historyData = [];
        for ($i = 0; $i < 15; $i++) {
            $rumahId = Str::uuid();
            $randomPenghuniId = $tetapPenghuniIds[array_rand($tetapPenghuniIds)];

            DB::table('rumah')->insert([
                'id' => $rumahId,
                'no_rumah' => ($i + 1),
                'Status_Rumah' => 'Dihuni',
                'penghuni_id' => $randomPenghuniId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add entry to history_penghuni_rumah
            $historyData[] = [
                'id' => Str::uuid(),
                'rumah_id' => $rumahId,
                'penghuni_id' => $randomPenghuniId,
                'Tanggal_Mulai' => now()->toDateString(), // Assuming the start date is now for 'Tetap'
                'Tanggal_Selesai' => null, // No end date for 'Tetap'
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert rumah with penghuni having 'Kontrak' status
        for ($i = 0; $i < 5; $i++) {
            $rumahId = Str::uuid();
            $randomPenghuniId = $kontrakPenghuniIds[array_rand($kontrakPenghuniIds)];


            DB::table('rumah')->insert([
                'id' => $rumahId,
                'no_rumah' => ($i + 16),
                'Status_Rumah' => 'Dihuni',
                'penghuni_id' => $randomPenghuniId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add entry to history_penghuni_rumah
            $historyData[] = [
                'id' => Str::uuid(),
                'rumah_id' => $rumahId,
                'penghuni_id' => $randomPenghuniId,
                'Tanggal_Mulai' => now()->toDateString(), // Assuming the start date is now for 'Kontrak'
                'Tanggal_Selesai' => now()->addMonth(6)->toDateString(), // End date assumed to be 6 months from now
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert history data into history_penghuni_rumah
        DB::table('history_penghuni_rumah')->insert($historyData);

        // Insert pembayaran and detail pembayaran
        for ($i = 0; $i < 5; $i++) {
            // Create a new pembayaran entry
            $pembayaranId = Str::uuid();
            $penghuniId = $tetapPenghuniIds[array_rand($tetapPenghuniIds)]; // Randomly pick a 'Tetap' penghuni

            DB::table('pembayaran')->insert([
                'id' => $pembayaranId,
                'penghuni_id' => $penghuniId,
                'Tanggal_Pembayaran' => now()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create detail pembayaran entries
            $detailData = [];
            for ($j = 0; $j < 3; $j++) {
                $detailData[] = [
                    'id' => Str::uuid(),
                    'pembayaran_id' => $pembayaranId,
                    'Iuran_Satpam' => $faker->randomFloat(2, 50000, 150000), // Random amount between 50,000 and 150,000
                    'Iuran_Kebersihan' => $faker->randomFloat(2, 25000, 75000), // Random amount between 25,000 and 75,000
                    'Tahun' => '2024',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('detail_pembayaran')->insert($detailData);
        }
    }
}
