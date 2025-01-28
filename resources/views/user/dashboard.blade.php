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
                <form method="GET" action="{{ route('dashboard') }}">
                    <select name="filter" class="border border-gray-300 rounded px-4 py-2 w-32" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="PTS" {{ request('filter') == 'PTS' ? 'selected' : '' }}>PTS</option>
                        <option value="PTN" {{ request('filter') == 'PTN' ? 'selected' : '' }}>PTN</option>
                    </select>
                </form>
            </div>
            <table class="table-auto w-full border-collapse border border-gray-200 text-left">
                <thead>
                    <tr>
                        <th class="border border-gray-200 px-4 py-2">Nama Universitas</th>
                        <th class="border border-gray-200 px-4 py-2">SPP</th>
                        <th class="border border-gray-200 px-4 py-2">Akreditasi</th>
                        <th class="border border-gray-200 px-4 py-2">Tenaga Pendidik (S3)</th>
                        <th class="border border-gray-200 px-4 py-2">Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($universities as $university)
                        <tr>
                            <td class="border border-gray-200 px-4 py-2">{{ $university->nama }}</td>
                            <td class="border border-gray-200 px-4 py-2">Rp {{ number_format($university->spp, 0, ',', '.') }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $university->akreditasi }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $university->dosen_s3 }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ $university->lokasi }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
