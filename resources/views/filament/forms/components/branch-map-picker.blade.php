
@once
    <link rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endonce

<div
    wire:ignore
    x-data="{
        map: null,
        marker: null,

        initMap() {
            const latInput = document.querySelector('[id$=latitude]');
            const lngInput = document.querySelector('[id$=longitude]');

            const initialLat = parseFloat(latInput?.value) || -6.1754;
            const initialLng = parseFloat(lngInput?.value) || 106.8272;

            this.map = L.map(this.$refs.map).setView(
                [initialLat, initialLng], 15
            );

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(this.map);

            this.marker = L.marker(
                [initialLat, initialLng],
                { draggable: true }
            ).addTo(this.map);

            this.marker.bindPopup('Lokasi cabang').openPopup();

            this.marker.on('dragend', () => {
                this.updateCoordinates(this.marker.getLatLng());
            });

            this.map.on('click', (e) => {
                this.marker.setLatLng(e.latlng);
                this.updateCoordinates(e.latlng);
            });

            this.map.whenReady(() => {
                setTimeout(() => this.map.invalidateSize(), 200);
            });
        },

        updateCoordinates(position) {
            const lat = position.lat.toFixed(7);
            const lng = position.lng.toFixed(7);

            const latInput = document.querySelector('[id$=latitude]');
            const lngInput = document.querySelector('[id$=longitude]');

            if (latInput) {
                latInput.value = lat;
                latInput.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
                latInput.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            }

            if (lngInput) {
                lngInput.value = lng;
                lngInput.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
                lngInput.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            }
        }
    }"
    x-init="initMap()"
>
    <div class="mb-2">
        <h3 class="text-base font-semibold">
            Pilih Lokasi Cabang
        </h3>
        <p class="text-sm text-gray-500">
            Geser marker atau klik peta untuk menentukan lokasi.
        </p>
    </div>

    <div
        x-ref="map"
        style="height: 400px; width: 100%; border-radius: 10px; z-index: 1;"
    ></div>
</div>
