<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk autentikasi
use App\Models\University;

class UniversityController extends Controller
{
    /**
     * Tampilkan dashboard universitas.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter');

        // Ambil data universitas berdasarkan filter
        $universities = $filter 
            ? University::where('pt', $filter)->get() 
            : University::all();

        return view('dashboard', ['universities' => $universities]);
    }

    public function range(Request $request)
    {
        // Ambil filter dari request
        $filter = $request->get('filter');
    
        // Ambil data universitas berdasarkan filter
        $universities = $filter 
            ? University::where('pt', $filter)->get() 
            : University::all();
    
        // Map setiap universitas dengan atribut yang sudah dirange
        $mappedUniversities = $universities->map(function ($university) {
            return [
                'nama' => $university->nama,
                'spp' => $university->spp,
                'dosen_s3' => $university->dosen_s3,
                'lokasi' => $university->lokasi,
                'mapped' => $university->getMappedAttributes(),
            ];
        });
    
        // Kirim data ke view
        return view('range', [
            'mappedUniversities' => $mappedUniversities,
            'filter' => $filter, // Kirim filter ke view untuk menjaga opsi yang dipilih
        ]);
    }
    
    public function normalisasi(Request $request)
{
    // Ambil filter dari request
    $filter = $request->get('filter');

    // Ambil data universitas berdasarkan filter (untuk ditampilkan)
    $universities = $filter 
        ? University::where('pt', $filter)->get() 
        : University::all();

    // Normalisasi berdasarkan semua data (global)
    $normalizedUniversities = University::normalizeData($universities);

    // Kirim data ke view
    return view('normalisasi', [
        'normalizedUniversities' => $normalizedUniversities,
        'filter' => $filter, // Kirim filter ke view
    ]);
}

    
public function ranking(Request $request)
{
    // Ambil filter dari request
    $filter = $request->get('filter');
    

    // Ambil data berdasarkan filter
    $filteredUniversities = $filter 
        ? University::where('pt', $filter)->get() 
        : University::all();

    // Hitung ranking berdasarkan bobot dan normalisasi global
    $rankedUniversities = University::rankUniversitiesWithWeights($filteredUniversities);

    // Kirim data ke view
    return view('ranking', [
        'rankedUniversities' => $rankedUniversities, 
        'filter' => $filter,
    ]);
}




    
    public function forminput()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Ambil data universitas dari database
        $universities = University::all();

        // Kirim data ke view
        return view('forminput', ['universities' => $universities]);
    }

    /**
     * Logout pengguna.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'id' => 'required|string|max:50',
            'nama' => 'required|string|max:100',
            'spp' => 'required|numeric|min:0',
            'akreditasi' => 'required|string|max:50',
            'dosen_s3' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:50',
        ]);

        // Simpan data ke database
        \App\Models\University::create($validated);

        // Redirect kembali ke halaman utama
        return redirect()->route('dashboard')->with('success', 'Data universitas berhasil ditambahkan!');
    }

    public function inputbobot()
    {
        return view('user.input-bobot');
    }

    public function edit($id)
    {
        $university = \App\Models\University::findOrFail($id);
        return view('edit', compact('university'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'spp' => 'required|numeric|min:0',
            'akreditasi' => 'required|string|max:255',
            'dosen_s3' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:50',
        ]);

        // Perbarui data di database
        $university = \App\Models\University::findOrFail($id);
        $university->update($validated);

        // Redirect kembali ke halaman utama dengan pesan sukses
        return redirect()->route('data')->with('success', 'Data universitas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Cari data berdasarkan ID
        $university = \App\Models\University::findOrFail($id);
        
        // Hapus data dari database
        $university->delete();

        // Redirect kembali ke halaman utama dengan pesan sukses
        return redirect()->route('data')->with('success', 'Data universitas berhasil dihapus!');
    }

}
