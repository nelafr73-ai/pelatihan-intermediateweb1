
@php
    $record = $getState();

    $lat = (float) $record->latitude;
    $lng = (float) $record->longitude;
    $nama = $record->nama;
@endphp

<div
    x-data="{
        map: null,

        async init() {
            // Memuat CSS Leaflet
            if (!document.querySelector('#leaflet-css')) {
                const css = document.createElement('link');
                css.id = 'leaflet-css';
                css.rel = 'stylesheet';
                css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                document.head.appendChild(css);
            }

            // Memuat JavaScript Leaflet
            if (!window.L) {
                await new Promise((resolve, reject) => {
                    let script = document.querySelector('#leaflet-js');

                    if (!script) {
                        script = document.createElement('script');
                        script.id = 'leaflet-js';
                        script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                        script.onload = resolve;
                        script.onerror = reject;
                        document.head.appendChild(script);
                    } else {
                        script.addEventListener('load', resolve, { once: true });

                        if (window.L) resolve();
                    }
                });
            }

            this.createMap();
        },

        createMap() {
            const lat = {{ $lat }};
            const lng = {{ $lng }};

            this.map = L.map(this.$refs.map).setView(
                [lat, lng], 16
            );

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(this.map);

            L.marker([lat, lng])
                .addTo(this.map)
                .bindPopup(@js($nama))
                .openPopup();

            setTimeout(() => {
                this.map.invalidateSize();
            }, 300);
        }
    }"
>
    <div class="mb-3">
        <h3 class="text-base font-semibold">
            Lokasi Cabang
        </h3>
        <p class="text-sm text-gray-500">
            Lokasi cabang berdasarkan koordinat yang tersimpan.
        </p>
    </div>

    <div
        x-ref="map"
        style="height: 400px; width: 100%; border-radius: 10px; z-index: 1;"
    ></div>
</div>
