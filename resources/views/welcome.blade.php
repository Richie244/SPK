@extends('layouts.app')

@section('hasil')
            <div class="container mx-auto p-6">
                <div class="bg-white p-6 shadow rounded-lg">
                    <h2 class="text-2xl font-bold mb-4">Data Universitas</h2>
                    <table class="table-auto w-full border-collapse border border-gray-200 text-left">
                        <thead>
                            <tr>
                                <th class="border border-gray-200 px-4 py-2">Nama Universitas</th>
                                <th class="border border-gray-200 px-4 py-2">SPP</th>
                                <th class="border border-gray-200 px-4 py-2">Akreditasi</th>
                                <th class="border border-gray-200 px-4 py-2">Tenaga Pendidik (S3)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($universities as $university)
                                <tr>
                                    <td class="border border-gray-200 px-4 py-2">{{ $university['nama'] }}</td>
                                    <td class="border border-gray-200 px-4 py-2">Rp {{ number_format($university['spp'], 0, ',', '.') }}</td>
                                    <td class="border border-gray-200 px-4 py-2">{{ $university['akreditasi'] }}</td>
                                    <td class="border border-gray-200 px-4 py-2">{{ $university['dosen_s3'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-purple-700 text-white px-6 py-4 ">
        <h1 class="text-xl font-bold">Sistem Penilaian Universitas dengan Prodi Sistem Informasi Terbaik</h1>
    </div>
    <div class="container mx-auto p-6">
        <div class="bg-white p-6 shadow rounded-lg">
            <h2 class="text-2xl font-bold mb-4">Aplikasi SPK-SAW</h2>
            <p class="mb-4">SPK-SAW Penilaian Universitas dengan Prodi Sistem Informasi adalah salah satu Pendukung Keputusan Penilaian Universitas dengan Prodi Sistem Informasi yang menggunakan metode Simple Additive Weighting (SAW) dalam mengambil suatu keputusan yaitu mencari Universitas dengan Prodi Sistem Informasi terbaik.</p>
    </div>
</div>
@endsection
