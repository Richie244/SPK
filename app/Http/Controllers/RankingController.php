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
            'bobot.spp' => 'required|numeric|min:0|max:1',
            'bobot.akreditasi' => 'required|numeric|min:0|max:1',
            'bobot.dosen_s3' => 'required|numeric|min:0|max:1',
            'bobot.lokasi' => 'required|numeric|min:0|max:1',
        ]);

        // Simpan bobot di session
        session(['bobot' => $request->bobot]);

        // Redirect ke halaman ranking
        return redirect()->route('userranking');
    }

    // Menampilkan ranking universitas
    public function userranking(Request $request)
    {
        // Ambil filter (jika ada)
        $filter = $request->get('filter');

        // Ambil data universitas sesuai filter
        $query = University::query();
        if ($filter) {
            $query->where('pt', $filter);
        }
        $filteredUniversities = $query->get();

        // Pastikan ada data universitas sebelum perhitungan ranking
        if ($filteredUniversities->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada universitas yang sesuai filter.');
        }

        // Hitung ranking berdasarkan bobot yang sudah disimpan di session
        $rankedUniversities = University::userrankUniversitiesWithWeights($filteredUniversities, session('bobot'));

        // Kirim data ke view
        return view('user.user-ranking', [
            'rankedUniversities' => $rankedUniversities, 
            'filter' => $filter,
        ]);
    }
}
