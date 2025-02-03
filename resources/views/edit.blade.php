@extends('layouts.app')

@section('hasil')
<div class="min-h-screen bg-gray-100">
    <div class="bg-purple-700 text-white px-6 py-4">
        <h1 class="text-xl font-bold">Edit Data Universitas</h1>
    </div>
    <div class="container mx-auto p-6">
        <div class="bg-white p-6 shadow rounded-lg">
            <form action="{{ route('universities.update', $university->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Universitas</label>
                    <input type="text" id="nama" name="nama" value="{{ $university->nama }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="spp" class="block text-sm font-medium text-gray-700">SPP</label>
                    <input type="number" id="spp" name="spp" value="{{ $university->spp }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="akreditasi" class="block text-sm font-medium text-gray-700">Akreditasi</label>
                    <select id="akreditasi" name="akreditasi" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="Tidak Terakreditasi" {{ $university->akreditasi == 'Tidak Terakreditasi' ? 'selected' : '' }}>Tidak Terakreditasi</option>
                        <option value="Baik" {{ $university->akreditasi == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Baik Sekali" {{ $university->akreditasi == 'Baik Sekali' ? 'selected' : '' }}>Baik Sekali</option>
                        <option value="Unggul" {{ $university->akreditasi == 'Unggul' ? 'selected' : '' }}>Unggul</option>
                        <option value="Internasional" {{ $university->akreditasi == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="dosen_s3" class="block text-sm font-medium text-gray-700">Tenaga Pendidik (S3)</label>
                    <input type="number" id="dosen_s3" name="dosen_s3" value="{{ $university->dosen_s3 }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <input type="text" id="lokasi" name="lokasi" value="{{ $university->lokasi }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="pt" class="block text-sm font-medium text-gray-700">PT</label>
                    <select id="pt" name="pt" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="PTS" {{ $university->pt == 'PTS' ? 'selected' : '' }}>PTS</option>
                        <option value="PTN" {{ $university->pt == 'PTN' ? 'selected' : '' }}>PTN</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
                    <button type="button" onclick="location.href='{{ route('data') }}'" 
                            class="ml-2 bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
