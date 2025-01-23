@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-purple-700 text-white px-6 py-4">
        <h1 class="text-xl font-bold">Sistem Penilaian Universitas dengan Prodi Sistem Informasi Terbaik</h1>
    </div>
    <div class="container mx-auto p-6">
        <div class="bg-white p-6 shadow rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Normalisasi</h2>
                <form method="GET" action="{{ route('normalisasi') }}">
                    <select name="filter" class="border border-gray-300 rounded px-4 py-2 w-32" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="PTS" {{ request('filter') == 'PTS' ? 'selected' : '' }}>PTS</option>
                        <option value="PTN" {{ request('filter') == 'PTN' ? 'selected' : '' }}>PTN</option>
                    </select>
                </form>
            </div>
            <p class="mb-4">Sistem Penilaian Universitas dengan Prodi Sistem Informasi Terbaik</p>

            <table class="table-auto w-full border-collapse border border-gray-200 text-left">
                <thead>
                    <tr>
                        <th class="border border-gray-200 px-4 py-2">Nama Universitas</th>
                        <th class="border border-gray-200 px-4 py-2">C1</th>
                        <th class="border border-gray-200 px-4 py-2">C2</th>
                        <th class="border border-gray-200 px-4 py-2">C3</th>
                        <th class="border border-gray-200 px-4 py-2">C4</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($normalizedUniversities as $university)
                        <tr>
                            <td class="border border-gray-200 px-4 py-2">{{ $university['nama'] }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ number_format($university['normalized']['SPP'], 4) }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ number_format($university['normalized']['Akreditasi'], 4) }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ number_format($university['normalized']['DosenS3'], 4) }}</td>
                            <td class="border border-gray-200 px-4 py-2">{{ number_format($university['normalized']['Lokasi'], 4) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
