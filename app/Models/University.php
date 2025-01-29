<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BobotKriteria; // Import the BobotKriteria model

class University extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'universitas';

    // Kolom yang dapat diisi secara massal
    protected $fillable = ['id','nama', 'spp', 'akreditasi', 'dosen_s3','lokasi', 'weight'];

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
    
        // Mengonversi $universities menjadi koleksi
$universities = collect($universities);

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
         // Retrieve weights from the bobot_kriteria table
    $weights = BobotKriteria::all()->keyBy('kriteria');

    // Calculate scores for each university
    return $filteredUniversities->map(function ($university) use ($weights) {
        $normalized = [
            'SPP' => (float)$university->spp, // Cost
            'Akreditasi' => (float)$university->akreditasi, // Benefit
            'DosenS3' => (float)$university->dosen_s3, // Benefit
            'Lokasi' => (float)$university->lokasi, // Benefit
        ];

        // Normalisasi nilai SAW agar hasilnya desimal (di sini kita melakukan perhitungan berdasarkan bobot dan normalisasi)
        $normalizedData = self::normalizeData([$university])->first()['normalized'];

        // Calculate total score using the weights and handle missing weights
        $score = (
            ($weights['SPP']->custom_bobot ?? 0) * $normalizedData['SPP'] +
            ($weights['Akreditasi']->custom_bobot ?? 0) * $normalizedData['Akreditasi'] +
            ($weights['DosenS3']->custom_bobot ?? 0) * $normalizedData['DosenS3'] +
            ($weights['Lokasi']->custom_bobot ?? 0) * $normalizedData['Lokasi']
        );

        return [
            'nama' => $university->nama,
            'total_score' => round($score, 4), // Menyajikan skor dalam bentuk desimal dengan 4 angka di belakang koma
        ];
    })->sortByDesc('total_score')->values(); // Sort by score (descending)
    }
}
