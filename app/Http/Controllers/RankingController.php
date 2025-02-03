<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\University;

class RankingController extends Controller
{
    // Simpan bobot dan redirect ke halaman ranking
    public function storeWeights(Request $request)
{
    // Validasi input
    $request->validate([
        'user_location' => 'required|string',
        'bobot.spp' => 'required|numeric|min:0|max:1',
        'bobot.akreditasi' => 'required|numeric|min:0|max:1',
        'bobot.dosen_s3' => 'required|numeric|min:0|max:1',
        'bobot.lokasi' => 'required|numeric|min:0|max:1',
    ]);

    // Simpan bobot dan lokasi di session
    session(['bobot' => $request->bobot]);
    session(['user_location' => strtolower($request->user_location)]); // Pastikan lowercase untuk pencocokan

    // Redirect ke halaman ranking
    return redirect()->route('userranking');
}

public function userranking()
{
    // Ambil lokasi user dari session
    $userLocation = session('user_location', 'surabaya'); // Default Surabaya jika kosong
    $bobot = session('bobot', ['spp' => 0.4, 'akreditasi' => 0.2, 'dosen_s3' => 0.2, 'lokasi' => 0.2]);

    // Ambil semua universitas
    $filteredUniversities = University::all();

    // Pastikan ada data universitas sebelum perhitungan ranking
    if ($filteredUniversities->isEmpty()) {
        return redirect()->back()->with('error', 'Tidak ada universitas yang sesuai filter.');
    }

    // Hitung ranking berdasarkan bobot dan lokasi user
    $rankedUniversities = University::userrankUniversitiesWithWeights($filteredUniversities, $bobot, $userLocation);

    // Kirim data ke view
    return view('user.user-ranking', [
        'rankedUniversities' => $rankedUniversities,
        'userLocation' => $userLocation,
    ]);
}



}
