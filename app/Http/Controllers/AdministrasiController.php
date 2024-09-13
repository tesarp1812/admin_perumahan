<?php

namespace App\Http\Controllers;

use App\Models\DetailPembayaran;
use App\Models\HistoryRumah;
use App\Models\Pembayaran;
use App\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdministrasiController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function HistoryRumah()
    {
        $historyRumah = HistoryRumah::all();

        return response()->json($historyRumah);
    }

    public function dataPembayaran()
    {
        // Fetch all payments with their details
        $pembayaranData = DB::table('pembayaran')
            ->join('detail_pembayaran', 'pembayaran.id', '=', 'detail_pembayaran.pembayaran_id')
            ->select(
                'pembayaran.penghuni_id',
                'pembayaran.id as pembayaran_id',
                'detail_pembayaran.Iuran_Satpam',
                'detail_pembayaran.Iuran_Kebersihan',
                'detail_pembayaran.Tahun',
                'detail_pembayaran.created_at'
            )
            ->get()
            ->groupBy('penghuni_id'); // Group by penghuni_id

        $response = [];

        foreach ($pembayaranData as $penghuni_id => $details) {
            $response[] = [
                'penghuni_id' => $penghuni_id,
                'detail' => $details->map(function ($item) {
                    // Ensure created_at is a Carbon instance
                    $createdAt = Carbon::parse($item->created_at);

                    return [
                        'pembayaran_id' => $item->pembayaran_id,
                        'Iuran_Satpam' => $item->Iuran_Satpam,
                        'Iuran_Kebersihan' => $item->Iuran_Kebersihan,
                        'Tahun' => $item->Tahun,
                        'created_at' => $createdAt->format('d-m-Y') // Format date as 'Y-m-d'
                    ];
                })
            ];
        }

        // Return JSON response
        return response()->json($response);
    }

    public function Pembayaran(Request $request)
    {
        // Validasi input
        $request->validate([
            'penghuni_id' => 'required|string',
            'detail' => 'required|array',
            'detail.*.Iuran_Satpam' => 'required|numeric',
            'detail.*.Iuran_Kebersihan' => 'required|numeric',
            'detail.*.Tahun' => 'nullable|integer',
        ]);

        // Buat entri pembayaran
        $pembayaran = Pembayaran::create([
            'id' => (string) Str::uuid(),
            'penghuni_id' => $request['penghuni_id'],
            'Tanggal_Pembayaran' => now()->format('Y-m-d'), // Set default to current date
        ]);

        // Prepare detail data
        $detailData = [];
        foreach ($request['detail'] as $detail) {
            $detailData[] = [
                'id' => (string) Str::uuid(),
                'pembayaran_id' => $pembayaran->id, // Link to the created pembayaran
                'Iuran_Satpam' => $detail['Iuran_Satpam'],
                'Iuran_Kebersihan' => $detail['Iuran_Kebersihan'],
                'Tahun' => $detail['Tahun'] ?? null, // Use null if 'Tahun' is not provided
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
}
