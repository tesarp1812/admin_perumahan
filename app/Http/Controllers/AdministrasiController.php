<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use Illuminate\Support\Facades\Validator;
use App\Models\Iuran;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdministrasiController extends Controller
{

    public function testConnection()
    {
        try {
            DB::connection()->getPdo();
            return response()->json(['message' => 'Database connection successful']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Database connection failed: ' . $e->getMessage()], 500);
        }
    }

    public function getPembayaran()
    {
        $pembayaran = Pembayaran::with('details')->get();
        return response()->json($pembayaran);
    }


    public function getIuran()
    {
        $iuran = Iuran::all();

        return response()->json($iuran);
    }

    /**
     * Store created pembayaran with details in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePembayaran(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'penghuni_id' => 'required|exists:penghuni,id',
            'detail' => 'required|array',
            'detail.*.iuran_id' => 'required|exists:iuran,id',
            'detail.*.bulan' => 'nullable|integer',
            'detail.*.tahun' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        $errorMessages = [];

        // Check for existing payments for each detail entry
        foreach ($validatedData['detail'] as $detail) {
            // Get the Iuran using the iuran_id
            $iuran = Iuran::find($detail['iuran_id']);

            if ($iuran) {
                $exists = DetailPembayaran::where('iuran_id', $iuran->id)
                    ->where('bulan', $detail['bulan'])
                    ->where('tahun', $detail['tahun'])
                    ->whereHas('pembayaran', function ($query) use ($validatedData) {
                        $query->where('penghuni_id', $validatedData['penghuni_id']);
                    })
                    ->exists();

                if ($exists) {
                    // Include the Iuran name in the error message
                    $errorMessages[] = "Pembayaran untuk {$iuran->nama_iuran} pada bulan {$detail['bulan']} dan tahun {$detail['tahun']} sudah dibayar.";
                }
            } else {
                $errorMessages[] = "Iuran dengan ID '{$detail['iuran_id']}' tidak ditemukan.";
            }
        }

        // If there are any error messages, return them all
        if (!empty($errorMessages)) {
            return response()->json([
                'Massage' => $errorMessages
            ], 409); // Conflict status code
        }

        // Buat entri pembayaran
        $pembayaran = Pembayaran::create([
            'id' => (string) Str::uuid(),
            'penghuni_id' => $validatedData['penghuni_id'],
            'Tanggal_Pembayaran' => now()->format('Y-m-d'), // Set default to current date
        ]);

        // Prepare detail data
        $detailData = [];
        foreach ($validatedData['detail'] as $detail) {
            $detailData[] = [
                'id' => (string) Str::uuid(),
                'pembayaran_id' => $pembayaran->id, // Link to the created pembayaran
                'iuran_id' => $detail['iuran_id'],
                'bulan' => $detail['bulan'] ?? null, // Use null if 'Tahun' is not provided
                'tahun' => $detail['tahun'] ?? null, // Use null if 'Tahun' is not provided
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert detail pembayaran into the database
        DetailPembayaran::insert($detailData);

        // Kembalikan respons JSON dengan entri pembayaran dan detail
        return response()->json([
            'pembayaran' => $pembayaran,
            'detail' => $detailData,
        ], 201);
    }

    public function storePengeluaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Kategori_Pengeluaran' => 'required|string',
            'jumlah' => 'required|decimal'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        $pengeluaran = Pengeluaran::create([
            'id' => (string) Str::uuid(),
            'Tanggal_Pengeluaran' => now()->format('Y-m-d'),
            'Kategori_Pengeluaran' => $validatedData['Kategori_Pengeluaran'],
            'jumlah' => $validatedData['jumlah']
        ]);

        return response()->json([$pengeluaran], 201);
    }
}
