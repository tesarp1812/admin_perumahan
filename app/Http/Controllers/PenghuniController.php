<?php

namespace App\Http\Controllers;

use App\Models\Penghuni;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PenghuniController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Penghuni::findOrFail($id);
    }

    public function getPenghuni()
    {
        $penghuni = Penghuni::all();
        if ($penghuni->isEmpty()) {
            return response()->json(['error' => 'Data Not Found'], Response::HTTP_UNAUTHORIZED);
        }
        return response()->json($penghuni);
    }

    /**
     * Store a newly created penghuni in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima dari request
        $validator = Validator::make($request->all(), [
            'Nama_Lengkap' => 'required|string|max:255',
            'Status_Penghuni' => 'required|in:Kontrak,Tetap',
            'Nomor_Telepon' => 'nullable|string|max:20',
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

        // Menyimpan data penghuni
        $penghuni = Penghuni::create([
            'id' => (string) Str::uuid(),
            'Nama_Lengkap' => $validatedData['Nama_Lengkap'],
            'Foto_KTP' => $fotoKTPPath,
            'Status_Penghuni' => $validatedData['Status_Penghuni'],
            'Nomor_Telepon' => $validatedData['Nomor_Telepon'],
            'Status_Menikah' => $validatedData['Status_Menikah'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mengirimkan respon sukses dengan data penghuni yang baru dibuat
        return response()->json($penghuni, Response::HTTP_CREATED);
    }


    /**
     * Update the specified penghuni in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari request
        $validator = Validator::make($request->all(), [
            'Nama_Lengkap' => 'required|string|max:255',
            'Status_Penghuni' => 'required|in:Kontrak,Tetap',
            'Nomor_Telepon' => 'nullable|string|max:20',
            'Status_Menikah' => 'required|in:Ya,Tidak',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Temukan penghuni berdasarkan ID
        $penghuni = Penghuni::where('id', $id)->first();

        if (!$penghuni) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Perbarui data penghuni
        $penghuni->update([
            'Nama_Lengkap' => $request['Nama_Lengkap'],
            'Foto_KTP' => $request['Foto_KTP'],
            'Status_Penghuni' => $request['Status_Penghuni'],
            'Nomor_Telepon' => $request['Nomor_Telepon'],
            'Status_Menikah' => $request['Status_Menikah'],
            'updated_at' => now(),
        ]);

        // Mengirimkan respon sukses dengan data penghuni yang diperbarui
        return response()->json($penghuni);
    }


    // Remove the specified user from storage
    public function destroy(Request $request, $id)
    {

        $penghuni = Penghuni::find($id);

        if (!$penghuni) {
            return response()->json(['message' => 'penghuni not found'], 404);
        }

        $penghuni->delete();

        return response()->json(['message' => 'penghuni deleted successfully']);
    }


    /**
     * Decode base64 image and save it to storage.
     *
     * @param  string  $base64Image
     * @param  string  $prefix
     * @return string|null
     */
    private function decodeBase64Image($base64String, $filePrefix)
    {
        // Pisahkan header dan data base64
        $imageData = explode(',', $base64String);
        if (count($imageData) !== 2) {
            throw new \Exception('Invalid base64 image format');
        }

        // Ambil data base64
        $base64Image = $imageData[1];

        // Decode data base64
        $imageData = base64_decode($base64Image);

        // Tentukan ekstensi file berdasarkan header (misalnya, 'data:image/jpeg;base64' -> 'jpeg')
        $imageType = explode(';', explode(':', $imageData[0])[1])[0];
        $extension = explode('/', $imageType)[1];

        // Buat nama file unik
        $fileName = $filePrefix . '_' . time() . '.' . $extension;

        // Simpan file ke disk menggunakan Storage facade
        $filePath = 'uploads/' . $fileName;
        Storage::put($filePath, $imageData);

        return $filePath;
    }
}
