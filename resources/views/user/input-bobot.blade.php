@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-purple-700 text-white px-6 py-4">
        <h1 class="text-xl font-bold">Sistem Penilaian Universitas dengan Prodi Sistem Informasi Terbaik</h1>
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

        <div class="bg-white p-6 shadow rounded-lg mt-6">
            <h2 class="text-2xl font-bold">Google Autocomplete Address</h2>
            <form>
                <input id="autocomplete" type="text" placeholder="Enter your address" style="width: 100%; padding: 10px; font-size: 16px;" />
            </form>
            <br />
            <div id="map" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
<script>
    let autocomplete, map, marker;

    function initAutocomplete() {
        // Initialize Google Autocomplete
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('autocomplete'),
            { types: ['geocode'] }
        );

        // Initialize Google Map
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: -6.200000, lng: 106.816666 }, // Default to Jakarta
            zoom: 15,
        });

        marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29),
        });

        // Event listener for place selection
        autocomplete.addListener('place_changed', function () {
            marker.setVisible(false);
            const place = autocomplete.getPlace();

            if (!place.geometry) {
                alert("No details available for the selected address!");
                return;
            }

            // Set map center and marker
            map.setCenter(place.geometry.location);
            map.setZoom(15);
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
        });
    }

    // Initialize on window load
    window.onload = initAutocomplete;
</script>
@endsection
