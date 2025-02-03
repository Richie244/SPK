<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'universitas';

    // Kolom yang dapat diisi secara massal
    protected $fillable = ['id','nama', 'spp', 'akreditasi', 'dosen_s3','lokasi', 'pt'];


    // Fungsi untuk memetakan range data
    public function getMappedAttributes()
    {
        // Mapping Akreditasi
        $akreditasiMapping = [
            'Tidak Terakreditasi' => 0,
            'Baik' => 1,
            'Baik Sekali' => 2,
            'Unggul' => 3,
            'Internasional' => 4,
        ];
        $mappedAkreditasi = $akreditasiMapping[$this->akreditasi] ?? null;
    
        // Mapping Lokasi
        $mappedLokasi = (stripos($this->lokasi, 'Surabaya') !== false) ? 1 : 2;
    
        return [
            'Akreditasi' => $mappedAkreditasi,
            'Lokasi' => $mappedLokasi,
        ];
    }
    

    public static function normalizeData($universities)
    {
        // Ambil semua data untuk nilai min dan max global
        $allUniversities = University::all();
    
        // Mapping Akreditasi dan Lokasi
        $akreditasiMapping = [
            'Tidak Terakreditasi' => 0,
            'Baik' => 1,
            'Baik Sekali' => 2,
            'Unggul' => 3,
            'Internasional' => 4,
        ];
        $lokasiMapping = [
            'Satu Kota' => 1, // Lebih baik (cost lebih kecil)
            'Beda Kota' => 2, // Lebih buruk (cost lebih besar)
        ];
    
        // Nilai min dan max global
        $sppMin = max($allUniversities->pluck('spp')->min(), 1);
        $akreditasiMax = max($allUniversities->pluck('akreditasi')->map(fn($a) => $akreditasiMapping[$a] ?? 0)->max(), 1);
        $dosenS3Max = max($allUniversities->pluck('dosen_s3')->max(), 1);
        
        // Pastikan nilai min lokasi adalah yang terkecil dari semua universitas
        $lokasiMin = 1; // Karena 'Satu Kota' (Surabaya) harus memiliki nilai 1
    
        // Normalisasi data untuk setiap universitas
        return $universities->map(function ($university) use ($sppMin, $akreditasiMax, $dosenS3Max, $lokasiMin, $akreditasiMapping, $lokasiMapping) {
            // Tentukan apakah universitas ini di Surabaya atau tidak
            $lokasi = strpos(strtolower($university->lokasi), 'surabaya') !== false ? 'Satu Kota' : 'Beda Kota';
    
            // Normalisasi data
            $normalized = [
                'SPP' => $sppMin / max($university->spp, 1), // Cost (lebih kecil lebih baik)
                'Akreditasi' => ($akreditasiMapping[$university->akreditasi] ?? 0) / $akreditasiMax, // Benefit
                'DosenS3' => $university->dosen_s3 / max($dosenS3Max, 1), // Benefit
                'Lokasi' => $lokasiMin / max($lokasiMapping[$lokasi] ?? 1, 1), // Cost (lebih kecil lebih baik)
            ];
    
            return [
                'nama' => $university->nama,
                'normalized' => $normalized,
            ];
        });
    }
    
    
    public static function rankUniversitiesWithWeights($filteredUniversities)
    {
        // Ambil semua data untuk nilai min dan max global
        $allUniversities = University::all();
    
        // Mapping Akreditasi dan Lokasi
        $akreditasiMapping = [
            'Tidak Terakreditasi' => 0,
            'Baik' => 1,
            'Baik Sekali' => 2,
            'Unggul' => 3,
            'Internasional' => 4,
        ];
        $lokasiMapping = [
            'Satu Kota' => 1, // Lebih baik (cost lebih kecil)
            'Beda Kota' => 2, // Lebih buruk (cost lebih besar)
        ];
    
        // Nilai min dan max global
        $sppMin = max($allUniversities->pluck('spp')->min(), 1);
        $akreditasiMax = max($allUniversities->pluck('akreditasi')->map(fn($a) => $akreditasiMapping[$a] ?? 0)->max(), 1);
        $dosenS3Max = max($allUniversities->pluck('dosen_s3')->max(), 1);
        
        // Pastikan nilai min lokasi adalah yang terkecil dari semua universitas
        $lokasiMin = 1; // Karena 'Satu Kota' (Surabaya) harus memiliki nilai 1
    
        // Bobot kriteria (C1, C2, C3, C4)
        $weights = [
            'SPP' => 0.4, // Cost (lebih kecil lebih baik)
            'Akreditasi' => 0.2, // Benefit (lebih besar lebih baik)
            'DosenS3' => 0.2, // Benefit (lebih besar lebih baik)
            'Lokasi' => 0.2, // Cost (lebih kecil lebih baik)
        ];
    
        // Hitung skor total untuk setiap universitas
        return $filteredUniversities->map(function ($university) use ($sppMin, $akreditasiMax, $dosenS3Max, $lokasiMin, $akreditasiMapping, $lokasiMapping, $weights) {
            // Tentukan apakah universitas ini di Surabaya atau tidak
            $lokasi = strpos(strtolower($university->lokasi), 'surabaya') !== false ? 'Satu Kota' : 'Beda Kota';
    
            // Normalisasi data
            $normalized = [
                'SPP' => $sppMin / max($university->spp, 1), // Cost (lebih kecil lebih baik)
                'Akreditasi' => ($akreditasiMapping[$university->akreditasi] ?? 0) / $akreditasiMax, // Benefit
                'DosenS3' => $university->dosen_s3 / max($dosenS3Max, 1), // Benefit
                'Lokasi' => $lokasiMin / max($lokasiMapping[$lokasi] ?? 1, 1), // Cost (lebih kecil lebih baik)
            ];
    
            // Hitung skor total menggunakan bobot
            $score = (
                ($weights['SPP'] * $normalized['SPP']) +
                ($weights['Akreditasi'] * $normalized['Akreditasi']) +
                ($weights['DosenS3'] * $normalized['DosenS3']) +
                ($weights['Lokasi'] * $normalized['Lokasi'])
            );
    
            return [
                'nama' => $university->nama,
                'total_score' => round($score, 4),
            ];
        })->sortByDesc('total_score')->values(); // Urutkan berdasarkan skor (descending)
    }
    
    
    
    public static function userrankUniversitiesWithWeights($filteredUniversities, $weights, $userLocation)
{
    // Ambil semua universitas untuk mencari nilai min/max
    $allUniversities = University::all();

    // Mapping Akreditasi
    $akreditasiMapping = [
        'Tidak Terakreditasi' => 0,
        'Baik' => 1,
        'Baik Sekali' => 2,
        'Unggul' => 3,
        'Internasional' => 4,
    ];

    // Mapping Lokasi sebagai Cost
    $lokasiMapping = [
        'Satu Kota' => 1, // Cost lebih kecil (lebih baik)
        'Beda Kota' => 2, // Cost lebih besar (lebih buruk)
    ];

    // Min & Max global
    $sppMin = max($allUniversities->pluck('spp')->min(), 1);
    $akreditasiMax = max($allUniversities->pluck('akreditasi')->map(fn($a) => $akreditasiMapping[$a] ?? 0)->max(), 1);
    $dosenS3Max = max($allUniversities->pluck('dosen_s3')->max(), 1);
    $lokasiMin = min($lokasiMapping); // Nilai minimum lokasi harus 1

    return $filteredUniversities->map(function ($university) use ($sppMin, $akreditasiMax, $dosenS3Max, $lokasiMin, $akreditasiMapping, $lokasiMapping, $weights, $userLocation) {
        // Tentukan apakah universitas berada di kota user atau tidak
        $lokasi = (stripos($university->lokasi, $userLocation) !== false) ? 'Satu Kota' : 'Beda Kota';

        // Normalisasi data (perhitungan cost & benefit)
        $normalized = [
            'SPP' => $sppMin / max($university->spp, 1), // Cost (lebih kecil lebih baik)
            'Akreditasi' => ($akreditasiMapping[$university->akreditasi] ?? 0) / $akreditasiMax, // Benefit (lebih besar lebih baik)
            'DosenS3' => $university->dosen_s3 / max($dosenS3Max, 1), // Benefit (lebih besar lebih baik)
            'Lokasi' => $lokasiMin / max($lokasiMapping[$lokasi] ?? 1, 1), // Cost (lebih kecil lebih baik)
        ];

        // Hitung skor total menggunakan bobot dari session
        $score = (
            ($weights['spp'] * $normalized['SPP']) +
            ($weights['akreditasi'] * $normalized['Akreditasi']) +
            ($weights['dosen_s3'] * $normalized['DosenS3']) +
            ($weights['lokasi'] * $normalized['Lokasi'])
        );

        return [
            'nama' => $university->nama,
            'total_score' => round($score, 4),
        ];
    })->sortByDesc('total_score')->values(); // Urutkan berdasarkan skor total (descending)
}

}
