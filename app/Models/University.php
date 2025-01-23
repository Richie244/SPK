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
    protected $fillable = ['id','nama', 'spp', 'akreditasi', 'dosen_s3','lokasi'];

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
    
        // Ubah nilai akreditasi dan lokasi dari string ke angka berdasarkan mapping
        $universities = $universities->map(function ($university) use ($akreditasiMapping, $lokasiMapping) {
            $university->akreditasi = $akreditasiMapping[$university->akreditasi] ?? 0; // Default 0 jika tidak ditemukan
            $university->lokasi = $lokasiMapping[$university->lokasi] ?? 0; // Default 0 jika tidak ditemukan
            return $university;
        });
    
        // Ambil nilai SPP, Akreditasi, Dosen S3, dan Lokasi dari semua data
        $sppValues = $universities->pluck('spp');
        $akreditasiValues = $universities->pluck('akreditasi');
        $dosenS3Values = $universities->pluck('dosen_s3');
        $lokasiValues = $universities->pluck('lokasi');
    
        // Hitung nilai min dan max untuk setiap kriteria
        $sppMin = $sppValues->min();
        $akreditasiMax = $akreditasiValues->max();
        $dosenS3Max = $dosenS3Values->max();
        $lokasiMin = $lokasiValues->min();
    
        // Normalisasi setiap data universitas
        return $universities->map(function ($university) use ($sppMin, $akreditasiMax, $dosenS3Max, $lokasiMin) {
            return [
                'nama' => $university->nama,
                'normalized' => [
                    'SPP' => $sppMin / $university->spp, // Cost
                    'Akreditasi' => $university->akreditasi / $akreditasiMax, // Benefit
                    'DosenS3' => $university->dosen_s3 / $dosenS3Max, // Benefit
                    'Lokasi' => $lokasiMin / $university->lokasi, // Benefit
                ],
            ];
        });
    }
    
    

    
    public static function rankUniversitiesWithWeights($universities, $filter = null)
    {
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
        
        // Ubah semua data (tanpa filter) untuk normalisasi
        $allUniversities = $universities->map(function ($university) use ($akreditasiMapping, $lokasiMapping) {
            $university->akreditasi = $akreditasiMapping[$university->akreditasi] ?? 0;
            $university->lokasi = $lokasiMapping[$university->lokasi] ?? 0;
            return $university;
        });
        
        // Ambil nilai min dan max dari seluruh dataset
        $sppMin = $allUniversities->pluck('spp')->min();
        $akreditasiMax = $allUniversities->pluck('akreditasi')->max();
        $dosenS3Max = $allUniversities->pluck('dosen_s3')->max();
        $lokasiMin = $allUniversities->pluck('lokasi')->min();
        
        $weights = [
            'SPP' => 0.4,
            'Akreditasi' => 0.2,
            'DosenS3' => 0.2,
            'Lokasi' => 0.2,
        ];
        
        // Normalisasi dan hitung total skor
        $rankedUniversities = $universities->map(function ($university) use ($sppMin, $akreditasiMax, $dosenS3Max, $lokasiMin, $weights) {
            $normalizedSPP = $sppMin / $university->spp;
            $normalizedAkreditasi = $university->akreditasi / $akreditasiMax;
            $normalizedDosenS3 = $university->dosen_s3 / $dosenS3Max;
            $normalizedLokasi = $lokasiMin / $university->lokasi;
            
            $totalScore = 
                ($normalizedSPP * $weights['SPP']) +
                ($normalizedAkreditasi * $weights['Akreditasi']) +
                ($normalizedDosenS3 * $weights['DosenS3']) +
                ($normalizedLokasi * $weights['Lokasi']);
            
            return [
                'nama' => $university->nama,
                'normalized' => [
                    'SPP' => $normalizedSPP,
                    'Akreditasi' => $normalizedAkreditasi,
                    'DosenS3' => $normalizedDosenS3,
                    'Lokasi' => $normalizedLokasi,
                ],
                'total_score' => $totalScore,
                'filter' => $university->filter,
            ];
        });
        
        // Terapkan filter (jika ada)
        if ($filter) {
            $rankedUniversities = $rankedUniversities->filter(function ($university) use ($filter) {
                return $university['filter'] == $filter;
            });
        }
        
        return $rankedUniversities->sortByDesc('total_score')->values();
    }
    
    
    

    

}




