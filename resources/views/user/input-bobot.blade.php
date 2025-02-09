@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-purple-700 text-white px-6 py-4">
        <h1 class="text-xl font-bold">Sistem Penilaian Universitas</h1>
    </div>
    <div class="container mx-auto p-6">
        <div class="bg-white p-6 shadow rounded-lg">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Input Bobot Kriteria</h1>
            <form action="{{ route('storeWeights') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="spp" class="block text-sm font-medium text-gray-700">Bobot SPP</label>
                        <input type="number" step="0.01" name="bobot[spp]" id="spp" 
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" 
                               value="{{ session('bobot.spp', 0.4) }}" required>
                    </div>
                    <div>
                        <label for="akreditasi" class="block text-sm font-medium text-gray-700">Bobot Akreditasi</label>
                        <input type="number" step="0.01" name="bobot[akreditasi]" id="akreditasi" 
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" 
                               value="{{ session('bobot.akreditasi', 0.2) }}" required>
                    </div>
                    <div>
                        <label for="dosen_s3" class="block text-sm font-medium text-gray-700">Bobot Dosen S3</label>
                        <input type="number" step="0.01" name="bobot[dosen_s3]" id="dosen_s3" 
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" 
                               value="{{ session('bobot.dosen_s3', 0.2) }}" required>
                    </div>
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700">Bobot Lokasi</label>
                        <input type="number" step="0.01" name="bobot[lokasi]" id="lokasi" 
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" 
                               value="{{ session('bobot.lokasi', 0.2) }}" required>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Simpan dan Lihat Ranking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
