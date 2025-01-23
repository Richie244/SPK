@extends('layouts.app')

@section('hasil')
<div class="min-h-screen bg-gray-100">
    <div class="bg-purple-700 text-white px-6 py-4">
        <h1 class="text-xl font-bold">Sistem Penilaian Universitas dengan Prodi Sistem Informasi Terbaik</h1>
    </div>
    <div class="container mx-auto p-6">
        <div class="bg-white p-6 shadow rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Data Universitas</h2>
                <button onclick="location.href='{{ route('universities.create') }}'" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-blue-600">Tambah</button>
            </div>
            <table class="table-auto w-full border-collapse border border-gray-200 text-left">
                <thead>
                    <tr>
                        <th class="border border-gray-200 px-4 py-2">Nama Universitas</th>
                        <th class="border border-gray-200 px-4 py-2">SPP</th>
                        <th class="border border-gray-200 px-4 py-2">Akreditasi</th>
                        <th class="border border-gray-200 px-4 py-2">Tenaga Pendidik (S3)</th>
                        <th class="border border-gray-200 px-4 py-2">Lokasi</th>
                        <th class="border border-gray-200 px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($universities as $university)
                        <tr>
                            <td class="border border-gray-200 px-4 py-2">{{ $university['nama'] }}</td>
                            <td class="border border-gray-200 px-4 py-2">Rp {{ number_format($university['spp'], 0, ',', '.') }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $university['akreditasi'] }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $university['dosen_s3'] }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $university['lokasi'] }}</td>
                            <td class="border border-gray-200 px-4 py-2 text-center">
                                    <button onclick="location.href='{{ route('universities.edit', $university->id) }}'" 
                                            class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</button>
                                            <form action="{{ route('universities.destroy', $university->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                            </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
