@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Input Bobot Kriteria</h1>
        @php
            $defaultWeights = [
                'spp' => 0.4, // Default value for SPP
                'akreditasi' => 0.2, // Default value for Akreditasi
                'dosen_s3' => 0.2, // Default value for Dosen S3
                'lokasi' => 0.2 // Default value for Lokasi
            ];
        @endphp
        <form id="bobotForm" action="{{ route('bobot.store') }}" method="POST">
            @csrf
            <div class="grid gap-4">
                <div>
                    <label for="spp" class="block text-gray-700">Bobot SPP</label>
                    <input type="number" step="0.01" name="bobot[spp]" id="spp" class="w-full px-4 py-2 border rounded" value="{{ $defaultWeights['spp'] }}" required>
                </div>
                <div>
                    <label for="akreditasi" class="block text-gray-700">Bobot Akreditasi</label>
                    <input type="number" step="0.01" name="bobot[akreditasi]" id="akreditasi" class="w-full px-4 py-2 border rounded" value="{{ $defaultWeights['akreditasi'] }}" required>
                </div>
                <div>
                    <label for="dosen_s3" class="block text-gray-700">Bobot Dosen S3</label>
                    <input type="number" step="0.01" name="bobot[dosen_s3]" id="dosen_s3" class="w-full px-4 py-2 border rounded" value="{{ $defaultWeights['dosen_s3'] }}" required>
                </div>
                <div>
                    <label for="lokasi" class="block text-gray-700">Bobot Lokasi</label>
                    <input type="number" step="0.01" name="bobot[lokasi]" id="lokasi" class="w-full px-4 py-2 border rounded" value="{{ $defaultWeights['lokasi'] }}" required>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="px-6 py-2 bg-purple-700 text-white rounded hover:bg-purple-800">Simpan</button>
            </div>
        </form>
        <div id="successMessage" class="bg-green-500 text-white p-4 rounded mb-4 hidden"></div>
        <div id="errorMessage" class="bg-red-500 text-white p-4 rounded mb-4 hidden"></div>
    </div>
</div>

<script>
    document.getElementById('bobotForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this);
        fetch('{{ route('bobot.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response Status:', response.status); // Log the response status
            return response.json();
        })
        .then(data => {
            console.log('Response Data:', data); // Log the response data
            if (data.success) {
                document.getElementById('successMessage').innerText = data.success;
                document.getElementById('successMessage').classList.remove('hidden');
                document.getElementById('errorMessage').classList.add('hidden');

                // Display ranking data
                const ranking = data.ranking; // Assuming ranking data is returned
                // Here you can implement how to display the ranking data
                console.log('Ranking Data:', ranking);
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            document.getElementById('errorMessage').innerText = 'An error occurred. Please try again.';
            document.getElementById('errorMessage').classList.remove('hidden');
        });
    });
</script>
@endsection
