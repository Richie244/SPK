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

        $lokasiMapping = [
            'Satu Kota' => 1,
            'Beda Kota' => 2,
        ];
        $mappedLokasi = $lokasiMapping[$this->lokasi] ?? null;

        return [
            'Akreditasi' => $mappedAkreditasi,
            'Lokasi' => $mappedLokasi,
        ];
    }

    public static function normalizeData($universities)
    {
        // Ambil semua data untuk menghitung nilai global min dan max
        $allUniversities = University::all();
    
        // Mapping Akreditasi
        $akreditasiMapping = [
            'Tidak Terakreditasi' => 0,
            'Baik' => 1,
            'Baik Sekali' => 2,
            'Unggul' => 3,
            'Internasional' => 4,
        ];
    
        // Mapping Lokasi
        $lokasiMapping = [
            'Satu Kota' => 1,
            'Beda Kota' => 2,
        ];
    
        // Hitung min dan max dari semua data (global)
        $sppMin = $allUniversities->pluck('spp')->min();
        $akreditasiMax = $allUniversities->pluck('akreditasi')->map(function ($a) use ($akreditasiMapping) {
            return $akreditasiMapping[$a] ?? 0;
        })->max();
        $dosenS3Max = $allUniversities->pluck('dosen_s3')->max();
        $lokasiMin = $allUniversities->pluck('lokasi')->map(function ($l) use ($lokasiMapping) {
            return $lokasiMapping[$l] ?? 0;
        })->min();
    
        // Normalisasi data berdasarkan nilai global
        return $universities->map(function ($university) use ($sppMin, $akreditasiMax, $dosenS3Max, $lokasiMin, $akreditasiMapping, $lokasiMapping) {
            return [
                'nama' => $university->nama,
                'normalized' => [
                    'SPP' => $sppMin / $university->spp, // Cost
                    'Akreditasi' => ($akreditasiMapping[$university->akreditasi] ?? 0) / $akreditasiMax, // Benefit
                    'DosenS3' => $university->dosen_s3 / $dosenS3Max, // Benefit
                    'Lokasi' => $lokasiMin / ($lokasiMapping[$university->lokasi] ?? 1), // Benefit
                ],
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
            'Satu Kota' => 1,
            'Beda Kota' => 2,
        ];
    
        // Nilai min dan max global
        $sppMin = $allUniversities->pluck('spp')->min();
        $akreditasiMax = $allUniversities->pluck('akreditasi')->map(function ($a) use ($akreditasiMapping) {
            return $akreditasiMapping[$a] ?? 0;
        })->max();
        $dosenS3Max = $allUniversities->pluck('dosen_s3')->max();
        $lokasiMin = $allUniversities->pluck('lokasi')->map(function ($l) use ($lokasiMapping) {
            return $lokasiMapping[$l] ?? 0;
        })->min();
    
        // Bobot kriteria (C1, C2, C3, C4)
        $weights = [
            'SPP' => 0.4, // Bobot untuk SPP (Cost)
            'Akreditasi' => 0.2, // Bobot untuk Akreditasi (Benefit)
            'DosenS3' => 0.2, // Bobot untuk Dosen S3 (Benefit)
            'Lokasi' => 0.2, // Bobot untuk Lokasi (Benefit)
        ];
    
        // Hitung skor total untuk setiap universitas
        return $filteredUniversities->map(function ($university) use ($sppMin, $akreditasiMax, $dosenS3Max, $lokasiMin, $akreditasiMapping, $lokasiMapping, $weights) {
            $normalized = [
                'SPP' => $sppMin / $university->spp, // Cost
                'Akreditasi' => ($akreditasiMapping[$university->akreditasi] ?? 0) / $akreditasiMax, // Benefit
                'DosenS3' => $university->dosen_s3 / $dosenS3Max, // Benefit
                'Lokasi' => $lokasiMin / ($lokasiMapping[$university->lokasi] ?? 1), // Benefit
            ];
    
            // Hitung skor total menggunakan bobot
            $score = (
                $weights['SPP'] * $normalized['SPP'] +
                $weights['Akreditasi'] * $normalized['Akreditasi'] +
                $weights['DosenS3'] * $normalized['DosenS3'] +
                $weights['Lokasi'] * $normalized['Lokasi']
            );
    
            return [
                'nama' => $university->nama,
                'total_score' => $score,
            ];
        })->sortByDesc('total_score')->values(); // Urutkan berdasarkan skor (descending)
    }
    
    public static function userrankUniversitiesWithWeights($filteredUniversities, $weights)
{
    $allUniversities = University::all();
    
    // Mapping
    $akreditasiMapping = ['Tidak Terakreditasi' => 0, 'Baik' => 1, 'Baik Sekali' => 2, 'Unggul' => 3, 'Internasional' => 4];
    
    // Menambahkan pengecekan Surabaya untuk lokasi
    $lokasiMapping = ['Satu Kota' => 1, 'Beda Kota' => 2];

    // Min & Max global
    $sppMin = max($allUniversities->pluck('spp')->min(), 1);
    $akreditasiMax = max($allUniversities->pluck('akreditasi')->map(fn($a) => $akreditasiMapping[$a] ?? 0)->max(), 1);
    $dosenS3Max = max($allUniversities->pluck('dosen_s3')->max(), 1);

    return $filteredUniversities->map(function ($university) use ($sppMin, $akreditasiMax, $dosenS3Max, $lokasiMapping, $akreditasiMapping, $weights) {
        // Pengecekan lokasi berdasarkan kota
        $lokasi = strpos(strtolower($university->lokasi), 'surabaya') !== false ? 'Satu Kota' : 'Beda Kota';

        // Normalisasi
        $normalized = [
            'SPP' => $sppMin / max($university->spp, 1),
            'Akreditasi' => ($akreditasiMapping[$university->akreditasi] ?? 0) / $akreditasiMax,
            'DosenS3' => $university->dosen_s3 / max($dosenS3Max, 1),
            'Lokasi' => $lokasiMapping[$lokasi] ?? 2, // Default ke 'Beda Kota' jika lokasi tidak ditemukan
        ];

        $score = (
            ($weights['spp'] * $normalized['SPP']) +
            ($weights['akreditasi'] * $normalized['Akreditasi']) +
            ($weights['dosen_s3'] * $normalized['DosenS3']) +
            ($weights['lokasi'] * $normalized['Lokasi'])
        );

        return ['nama' => $university->nama, 'total_score' => round($score, 4)];
    })->sortByDesc('total_score')->values();
}

    

}
