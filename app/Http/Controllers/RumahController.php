<?php

namespace App\Http\Controllers;

use App\Models\Rumah;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RumahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rumahs = Rumah::all(); // Mengambil semua data rumah
        return response()->json($rumahs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Menampilkan form untuk membuat resource baru
        // (Biasanya digunakan untuk aplikasi berbasis web, tidak umum untuk API)
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima dari request
        $validator = Validator::make($request->all(), [
            'Status_Rumah' => 'required|in:Dihuni,Tidak Dihuni',
            'penghuni_id' => 'required|string|exists:penghuni,id', // Validasi penghuni_id harus ada di tabel penghuni
        ]);

        // Jika validasi gagal, kembalikan response dengan pesan kesalahan
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Menyimpan data rumah
        $rumah = Rumah::create([
            'id' => (string) Str::uuid(),
            'Status_Rumah' => $request->input('Status_Rumah'),
            'penghuni_id' => $request->input('penghuni_id'),
        ]);

        return response()->json($rumah, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rumah = Rumah::find($id);

        if (!$rumah) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        return response()->json($rumah);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Menampilkan form untuk mengedit resource
        // (Biasanya digunakan untuk aplikasi berbasis web, tidak umum untuk API)
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari request
        $validator = Validator::make($request->all(), [
            'Status_Rumah' => 'required|in:Dihuni,Tidak Dihuni',
            'penghuni_id' => 'required|string|exists:penghuni,id', // Validasi penghuni_id harus ada di tabel penghuni
        ]);

        // Jika validasi gagal, kembalikan response dengan pesan kesalahan
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $rumah = Rumah::find($id);

        if (!$rumah) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        // Mengupdate data rumah
        $rumah->update([
            'Status_Rumah' => $request->input('Status_Rumah'),
            'penghuni_id' => $request->input('penghuni_id'),
        ]);

        return response()->json($rumah);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rumah = Rumah::find($id);

        if (!$rumah) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        // Menghapus data rumah
        $rumah->delete();

        return response()->json(['message' => 'Resource deleted successfully']);
    }
}
