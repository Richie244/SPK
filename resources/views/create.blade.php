@extends('layouts.app')

@section('hasil')
<div class="min-h-screen bg-gray-100">
    <div class="bg-purple-700 text-white px-6 py-4">
        <h1 class="text-xl font-bold">Tambah Data Universitas</h1>
    </div>
    <div class="container mx-auto p-6">
        <div class="bg-white p-6 shadow rounded-lg">
            <form action="{{ route('universities.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="id" class="block text-sm font-medium text-gray-700">ID</label>
                    <input type="text" id="id" name="id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Universitas</label>
                    <input type="text" id="nama" name="nama" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="spp" class="block text-sm font-medium text-gray-700">SPP</label>
                    <input type="number" id="spp" name="spp" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="akreditasi" class="block text-sm font-medium text-gray-700">Akreditasi</label>
                    <select id="akreditasi" name="akreditasi" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="" disabled selected>Pilih Akreditasi</option>
                        <option value="Tidak Terakreditasi">Tidak Terakreditasi</option>
                        <option value="Baik">Baik</option>
                        <option value="Baik Sekali">Baik Sekali</option>
                        <option value="Unggul">Unggul</option>
                        <option value="Internasional">Internasional</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="dosen_s3" class="block text-sm font-medium text-gray-700">Tenaga Pendidik (S3)</label>
                    <input type="number" id="dosen_s3" name="dosen_s3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <select id="lokasi" name="lokasi" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="" disabled selected>Pilih Lokasi</option>
                        <option value="Satu Kota">Satu Kota</option>
                        <option value="Beda Kota">Beda Kota</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
                    <button type="button" onclick="location.href='{{ route('data') }}'" class="ml-2 bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
