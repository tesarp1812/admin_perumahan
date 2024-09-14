<?php

namespace App\Http\Controllers;

use App\Models\Penghuni;
use App\Models\Rumah;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PenghuniController extends Controller
{

    public function getWarga()
    {
        $warga = Warga::all();

        if ($warga->isEmpty()) {
            return response()->json(['error' => 'Data Not Found'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json($warga);
    }

    public function getWargaById($id)
    {
        $warga = Warga::findOrFail($id);

        return response()->json($warga);
    }

     /**
     * Store a newly created warga in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWarga(Request $request)
    {
        // Validasi data yang diterima dari request
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'Status_Menikah' => 'required|in:Ya,Tidak',
        ]);

        // Jika validasi gagal, kembalikan response dengan pesan kesalahan
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil data yang sudah tervalidasi
        $validatedData = $validator->validated();

        // Menyimpan file jika ada
        $fotoKTPPath = null;
        if ($request->hasFile('Foto_KTP')) {
            $fotoKTPPath = $request->file('Foto_KTP')->store('foto_ktp', 'public');
        }

        // Menyimpan data warga
        $warga = Warga::create([
            'id' => (string) Str::uuid(),
            'nama' => $validatedData['nama'],
            'Foto_KTP' => $fotoKTPPath,
            'Nomor_Telepon' => $request->input('Nomor_Telepon'),
            'Status_Menikah' => $validatedData['Status_Menikah'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mengirimkan respon sukses dengan data warga yang baru dibuat
        return response()->json($warga, Response::HTTP_CREATED);
    }

    public function getRumah()
    {
        $rumah = Rumah::orderBy('no_rumah', 'asc')->get();

        if ($rumah->isEmpty()) {
            return response()->json(['error' => 'Data Not Found'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json($rumah);
    }

    public function getRumahById($id)
    {
        $rumah = Rumah::findOrFail($id);

        return response()->json($rumah);
    }

    public function getPenghuni()
    {
        $penghuni = Penghuni::all();
        if ($penghuni->isEmpty()) {
            return response()->json(['error' => 'Data Not Found'], Response::HTTP_UNAUTHORIZED);
        }
        return response()->json($penghuni);
    }

    public function getPenghuniById($id)
    {
        return Penghuni::findOrFail($id);
    }

}
