<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Autocomplete Address</title>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Google Autocomplete Address</h1>
    <form>
        <input id="autocomplete" type="text" placeholder="Enter your address" style="width: 100%; padding: 10px; font-size: 16px;" />
    </form>
    <br />
    <div id="map"></div>

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
</body>
</html>