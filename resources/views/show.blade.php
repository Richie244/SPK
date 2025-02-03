@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 flex justify-center items-center">
    <div class="bg-white p-6 shadow rounded-lg w-1/2">
        <h1 class="text-2xl font-bold mb-4">Detail Universitas</h1>
        <p><strong>Nama:</strong> {{ $university->nama }}</p>
        <p><strong>SPP:</strong> {{ $university->spp }}</p>
        <p><strong>Akreditasi:</strong> {{ $university->akreditasi }}</p>
        <p><strong>Dosen S3:</strong> {{ $university->dosen_s3 }}</p>
        <p><strong>Lokasi:</strong> {{ $university->lokasi }}</p>
        <a href="{{ route('userranking') }}" class="mt-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Kembali
        </a>
    </div>
</div>
@endsection
