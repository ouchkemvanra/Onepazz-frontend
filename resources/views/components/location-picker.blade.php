@props(['latitude' => null, 'longitude' => null])

@php
$mapId = 'location-map-' . uniqid();
$lat = old('latitude', $latitude);
$lng = old('longitude', $longitude);
@endphp

<div>
    <label class="label">Pin Location on Map <span class="text-gray-400 font-normal">(click or drag the marker)</span></label>
    <div id="{{ $mapId }}" class="rounded-2xl overflow-hidden border border-gray-200" style="height: 320px;"></div>
    <input type="hidden" name="latitude" id="{{ $mapId }}-lat" value="{{ $lat }}">
    <input type="hidden" name="longitude" id="{{ $mapId }}-lng" value="{{ $lng }}">
    <p class="text-xs text-gray-400 mt-1.5">
        <span id="{{ $mapId }}-status">{{ $lat && $lng ? 'Location set.' : 'Click anywhere on the map to drop a pin at your studio\'s location.' }}</span>
    </p>
</div>

<script>
(function () {
    function init() {
        if (!window.L) { setTimeout(init, 100); return; }

        const startLat = {{ $lat ?: 11.5564 }};
        const startLng = {{ $lng ?: 104.9282 }};
        const hasInitial = {{ $lat && $lng ? 'true' : 'false' }};

        const map = window.L.map('{{ $mapId }}').setView([startLat, startLng], hasInitial ? 15 : 12);
        window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(map);

        const latInput = document.getElementById('{{ $mapId }}-lat');
        const lngInput = document.getElementById('{{ $mapId }}-lng');
        const status = document.getElementById('{{ $mapId }}-status');

        let marker = hasInitial ? window.L.marker([startLat, startLng], { draggable: true }).addTo(map) : null;

        function setPosition(lat, lng) {
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
            status.textContent = 'Location set: ' + lat.toFixed(5) + ', ' + lng.toFixed(5);

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = window.L.marker([lat, lng], { draggable: true }).addTo(map);
                marker.on('dragend', function () {
                    const pos = marker.getLatLng();
                    setPosition(pos.lat, pos.lng);
                });
            }
        }

        if (marker) {
            marker.on('dragend', function () {
                const pos = marker.getLatLng();
                setPosition(pos.lat, pos.lng);
            });
        }

        map.on('click', function (e) {
            setPosition(e.latlng.lat, e.latlng.lng);
        });
    }

    document.addEventListener('DOMContentLoaded', init);
})();
</script>
