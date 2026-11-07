<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Peta Cabang Toko Suku Cadang</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
  :root { --ink:#1c2a2e; --muted:#667479; --line:#e3e8e9; --bg:#f6f8f8; --accent:#0f766e; --accent-soft:#e1f2f0; --danger:#c0392b; }
  * { box-sizing:border-box; }
  html, body { height:100%; margin:0; font-family:system-ui,-apple-system,"Segoe UI",sans-serif; color:var(--ink); background:var(--bg); }
  .app { display:grid; grid-template-columns:360px 1fr; height:100%; }
  aside { background:#fff; border-right:1px solid var(--line); display:flex; flex-direction:column; min-height:0; }
  .head { padding:20px 20px 12px; }
  .head h1 { font-size:20px; margin:0 0 4px; }
  .head p { margin:0 0 14px; color:var(--muted); font-size:13px; }
  .legend { display:flex; gap:14px; font-size:12px; color:var(--muted); margin-bottom:12px; }
  .dot { display:inline-block; width:10px; height:10px; border-radius:50%; margin-right:5px; }
  .search { width:100%; padding:10px 12px; border:1px solid var(--line); border-radius:8px; font-size:14px; background:var(--bg); }
  .search:focus, input:focus, select:focus, textarea:focus { outline:2px solid var(--accent); outline-offset:1px; }
  .list { overflow:auto; padding:4px 12px 16px; flex:1; }
  .item { border:1px solid var(--line); border-radius:10px; padding:12px 14px; margin-bottom:10px; cursor:pointer; background:#fff; transition:border-color .15s, background .15s; }
  .item:hover { border-color:#b9c7c9; }
  .item.active { border-color:var(--accent); background:var(--accent-soft); }
  .item-top { display:flex; justify-content:space-between; align-items:center; gap:8px; }
  .item h3 { margin:0; font-size:15px; }
  .badge { font-size:11px; padding:2px 8px; border-radius:99px; font-weight:600; white-space:nowrap; text-transform:capitalize; }
  .badge.toko { background:#fdeaea; color:#b42318; }
  .badge.bengkel { background:#e6edff; color:#1d4ed8; }
  .addr { margin:6px 0 2px; font-size:13px; color:var(--muted); }
  .coord { font-size:12px; color:var(--muted); font-variant-numeric:tabular-nums; }
  .actions { display:flex; gap:8px; margin-top:10px; }
  button { font:inherit; cursor:pointer; }
  .btn { padding:6px 12px; border-radius:7px; font-size:13px; border:1px solid var(--line); background:#fff; color:var(--ink); }
  .btn:hover { background:var(--bg); }
  .btn.primary { background:var(--accent); border-color:var(--accent); color:#fff; }
  .btn.primary:hover { background:#0c5f58; }
  .btn:disabled { opacity:.6; cursor:wait; }
  .empty { text-align:center; color:var(--muted); font-size:14px; padding:30px 10px; }
  .mapwrap { position:relative; min-height:0; }
  #map { position:absolute; inset:0; }
  #btn-near { position:absolute; top:14px; right:14px; z-index:1000; box-shadow:0 2px 8px rgba(0,0,0,.15); padding:9px 14px; }
  .overlay { position:fixed; inset:0; background:rgba(20,30,32,.5); display:none; align-items:center; justify-content:center; z-index:2000; padding:16px; }
  .overlay.open { display:flex; }
  .modal { background:#fff; border-radius:14px; width:min(720px,100%); max-height:94vh; overflow:auto; padding:22px; }
  .modal h2 { margin:0 0 16px; font-size:19px; }
  .grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
  .full { grid-column:1 / -1; }
  label { display:block; font-size:13px; font-weight:600; margin-bottom:5px; }
  input, select, textarea { width:100%; padding:9px 11px; border:1px solid var(--line); border-radius:8px; font:inherit; font-size:14px; background:#fff; }
  .hint { font-size:12px; color:var(--muted); margin:4px 0 0; }
  #pickMap { height:300px; border-radius:10px; border:1px solid var(--line); }
  .foot { display:flex; justify-content:flex-end; gap:10px; margin-top:18px; align-items:center; }
  .msg { margin-right:auto; font-size:13px; }
  .msg.err { color:var(--danger); }
  @media (max-width:800px) { .app { grid-template-columns:1fr; grid-template-rows:45% 55%; } aside { order:2; border-right:0; border-top:1px solid var(--line); } .mapwrap { order:1; } .grid { grid-template-columns:1fr; } }
</style>
</head>
<body>

<div class="app">
  <aside>
    <div class="head">
      <h1>Daftar Cabang</h1>
      <p id="count"></p>
      <div class="legend">
        <span><i class="dot" style="background:red"></i>Toko</span>
        <span><i class="dot" style="background:blue"></i>Bengkel</span>
      </div>
      <input class="search" id="search" type="search" placeholder="Cari nama atau alamat cabang">
    </div>
    <div class="list" id="daftar-cabang"></div>
  </aside>
  <div class="mapwrap">
    <div id="map"></div>
    <button class="btn" id="btn-near">Cabang Terdekat</button>
  </div>
</div>

<div class="overlay" id="overlay" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
  <div class="modal">
    <h2 id="modalTitle">Ubah cabang</h2>
    <div class="grid">
      <div class="full"><label for="f_nama">Nama</label><input id="f_nama">
        <label class="hint" style="font-weight:400;display:flex;gap:6px;align-items:center;margin-top:6px">
          <input type="checkbox" id="f_auto" checked style="width:auto"> Isi nama otomatis sesuai titik di peta
        </label>
      </div>
      <div class="full"><label for="f_alamat">Alamat</label><textarea id="f_alamat" rows="2"></textarea></div>
      <div><label for="f_telepon">Telepon</label><input id="f_telepon"></div>
      <div><label for="f_type">Jenis lokasi</label>
        <select id="f_type"><option value="toko">Toko</option><option value="bengkel">Bengkel</option></select>
      </div>
      <div><label for="f_lat">Latitude</label><input id="f_lat" inputmode="decimal"><p class="hint">Contoh: -6.1754</p></div>
      <div><label for="f_lng">Longitude</label><input id="f_lng" inputmode="decimal"><p class="hint">Contoh: 106.8272</p></div>
      <div class="full">
        <label>Pilih lokasi cabang</label>
        <p class="hint" style="margin:0 0 6px">Geser marker atau klik peta untuk menentukan lokasi.</p>
        <div id="pickMap"></div>
      </div>
    </div>
    <div class="foot">
      <span class="msg" id="msg"></span>
      <button class="btn" id="cancelBtn" type="button">Batal</button>
      <button class="btn primary" id="saveBtn" type="button">Simpan perubahan</button>
    </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  // Data cabang dari Laravel (kolom: id, nama, alamat, telepon, type, latitude, longitude)
  const branches = @json($branches);
  const UPDATE_URL = id => `/cabang/${id}`; // route PUT, sesuaikan jika berbeda

  const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  const fmt = n => Number(n).toFixed(6);
  const $ = id => document.getElementById(id);

  // ---------- Peta utama ----------
  const map = L.map('map').setView([-6.2, 106.82], 12);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map);

  // Area layanan Jakarta
  L.polygon([[-6.20, 106.80], [-6.20, 106.85], [-6.15, 106.85], [-6.15, 106.80]])
    .addTo(map).bindPopup('Area layanan');

  // Area layanan Indramayu (GeoJSON)
  L.geoJSON({
    type: 'Feature',
    properties: { nama: 'Area Layanan Indramayu' },
    geometry: { type: 'Polygon', coordinates: [[[108.30,-6.35],[108.40,-6.35],[108.40,-6.25],[108.30,-6.25],[108.30,-6.35]]] }
  }, {
    style: { color: 'green', fillColor: '#00ff00', fillOpacity: 0.3 },
    onEachFeature: (f, layer) => layer.bindPopup(f.properties.nama)
  }).addTo(map);

  // ---------- Marker cabang ----------
  const markerColor = type => type === 'bengkel' ? 'blue' : 'red';
  const markers = {};   // dikunci dengan id cabang
  let activeId = null;

  const popupHtml = b =>
    `<b>${esc(b.nama)}</b><br>${esc(b.alamat)}<br>Jenis: ${esc(b.type)}`;

  function drawMarker(b) {
    if (markers[b.id]) map.removeLayer(markers[b.id]);
    const c = markerColor(b.type);
    markers[b.id] = L.circleMarker([Number(b.latitude), Number(b.longitude)], {
      radius: 9, color: c, fillColor: c, fillOpacity: 0.8
    }).addTo(map).bindPopup(popupHtml(b));
    markers[b.id].on('click', () => { activeId = b.id; renderList(); });
  }
  branches.forEach(b => { b.latitude = Number(b.latitude); b.longitude = Number(b.longitude); drawMarker(b); });

  function fitAll() {
    const ms = Object.values(markers);
    if (ms.length) map.fitBounds(L.featureGroup(ms).getBounds().pad(0.2));
  }
  fitAll();

  // ---------- Daftar cabang ----------
  function renderList() {
    const q = $('search').value.trim().toLowerCase();
    const rows = branches.filter(b => (b.nama + ' ' + (b.alamat || '')).toLowerCase().includes(q));
    $('count').textContent = `${rows.length} dari ${branches.length} cabang`;
    const el = $('daftar-cabang');
    if (!rows.length) {
      el.innerHTML = `<div class="empty">Tidak ada cabang yang cocok.
        ${q ? `<div style="margin-top:12px"><button class="btn primary" type="button" data-act="geo">Cari "${esc(q)}" di peta</button></div>` : ''}</div>`;
      return;
    }
    el.innerHTML = rows.map(b => `
      <div class="item ${b.id === activeId ? 'active' : ''}" data-id="${b.id}">
        <div class="item-top"><h3>${esc(b.nama)}</h3><span class="badge ${esc(b.type)}">${esc(b.type)}</span></div>
        <p class="addr">${esc(b.alamat || '-')}</p>
        <div class="coord">${fmt(b.latitude)}, ${fmt(b.longitude)}</div>
        <div class="actions">
          <button class="btn" type="button" data-act="view">Lihat</button>
          <button class="btn primary" type="button" data-act="edit">Ubah</button>
        </div>
      </div>`).join('');
  }
  function focusBranch(id) {
    const b = branches.find(x => x.id === id);
    activeId = id;
    map.setView([b.latitude, b.longitude], 16);
    markers[id].openPopup();
    renderList();
  }
  // Cari tempat di peta (OpenStreetMap / Nominatim)
  let placeMarker = null;
  async function searchPlace(q) {
    const el = $('daftar-cabang');
    el.innerHTML = '<div class="empty">Mencari lokasi...</div>';
    try {
      const res = await fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&limit=6&countrycodes=id&accept-language=id&q=${encodeURIComponent(q)}`);
      const data = await res.json();
      if (!data.length) { el.innerHTML = '<div class="empty">Lokasi tidak ditemukan. Coba kata kunci lain.</div>'; return; }
      el.innerHTML = data.map(d => `
        <div class="item geo-item" data-lat="${d.lat}" data-lng="${d.lon}" data-name="${esc(d.name || d.display_name)}">
          <div class="item-top"><h3>${esc(d.name || d.display_name.split(',')[0])}</h3></div>
          <p class="addr">${esc(d.display_name)}</p>
        </div>`).join('');
    } catch (err) {
      el.innerHTML = '<div class="empty">Gagal mencari lokasi. Periksa koneksi internet.</div>';
    }
  }
  $('search').addEventListener('input', renderList);
  $('search').addEventListener('keydown', e => {
    if (e.key === 'Enter' && $('search').value.trim()) searchPlace($('search').value.trim());
  });
  $('daftar-cabang').addEventListener('click', e => {
    if (e.target.closest('[data-act="geo"]')) return searchPlace($('search').value.trim());
    const geo = e.target.closest('.geo-item');
    if (geo) {
      const lat = Number(geo.dataset.lat), lng = Number(geo.dataset.lng);
      if (placeMarker) map.removeLayer(placeMarker);
      placeMarker = L.marker([lat, lng]).addTo(map).bindPopup(geo.dataset.name).openPopup();
      map.setView([lat, lng], 17);
      return;
    }
    const card = e.target.closest('.item');
    if (!card) return;
    const id = Number(card.dataset.id);
    if (e.target.closest('[data-act="edit"]')) return openEdit(id);
    focusBranch(id);
  });
  renderList();

  // ---------- Modal ubah ----------
  let pickMap, pickMarker, editing = null;

  function setLatLng(lat, lng, moveMarker = true) {
    $('f_lat').value = Number(lat).toFixed(6);
    $('f_lng').value = Number(lng).toFixed(6);
    if (moveMarker) pickMarker.setLatLng([lat, lng]);
  }
  const parseNum = v => parseFloat(String(v).replace(',', '.'));

  // Nama otomatis dari titik (reverse geocoding OpenStreetMap / Nominatim)
  let geoTimer = null, geoSeq = 0;
  function autoName(lat, lng) {
    if (!$('f_auto').checked) return;
    clearTimeout(geoTimer);
    geoTimer = setTimeout(async () => {
      const seq = ++geoSeq;
      try {
        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&zoom=18&accept-language=id&lat=${lat}&lon=${lng}`);
        const d = await res.json();
        if (seq !== geoSeq || !d || d.error) return;
        const a = d.address || {};
        const place = d.name || a.amenity || a.shop || a.building || a.road || a.neighbourhood || a.suburb || a.village || a.city_district || a.city || '';
        if (!place) return;
        $('f_nama').value = 'Cabang ' + place;
        if (d.display_name) $('f_alamat').value = d.display_name;
      } catch (e) { /* abaikan, nama tetap seperti sebelumnya */ }
    }, 700);
  }
  $('f_nama').addEventListener('input', () => { $('f_auto').checked = false; });

  function openEdit(id) {
    editing = branches.find(b => b.id === id);
    $('f_nama').value = editing.nama || '';
    $('f_alamat').value = editing.alamat || '';
    $('f_telepon').value = editing.telepon || '';
    $('f_type').value = editing.type === 'bengkel' ? 'bengkel' : 'toko';
    $('msg').textContent = ''; $('msg').className = 'msg';
    $('f_auto').checked = true;
    $('overlay').classList.add('open');

    if (!pickMap) {
      pickMap = L.map('pickMap');
      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(pickMap);
      pickMarker = L.marker([0, 0], { draggable: true }).addTo(pickMap);
      // Geser marker -> latitude & longitude ikut berubah
      pickMarker.on('drag', () => { const p = pickMarker.getLatLng(); setLatLng(p.lat, p.lng, false); });
      pickMarker.on('dragend', () => { const p = pickMarker.getLatLng(); setLatLng(p.lat, p.lng, false); autoName(p.lat, p.lng); });
      // Klik peta -> marker pindah
      pickMap.on('click', e => { setLatLng(e.latlng.lat, e.latlng.lng); autoName(e.latlng.lat, e.latlng.lng); });
    }
    setTimeout(() => {
      pickMap.invalidateSize();
      pickMap.setView([editing.latitude, editing.longitude], 16);
      setLatLng(editing.latitude, editing.longitude);
    }, 50);
  }

  // Ketik lat/lng -> marker ikut pindah
  ['f_lat', 'f_lng'].forEach(id => $(id).addEventListener('change', () => {
    const lat = parseNum($('f_lat').value), lng = parseNum($('f_lng').value);
    if (!isNaN(lat) && !isNaN(lng) && Math.abs(lat) <= 90 && Math.abs(lng) <= 180) {
      pickMarker.setLatLng([lat, lng]); pickMap.panTo([lat, lng]);
      autoName(lat, lng);
    }
  }));

  const closeModal = () => $('overlay').classList.remove('open');
  $('cancelBtn').addEventListener('click', closeModal);
  $('overlay').addEventListener('click', e => { if (e.target.id === 'overlay') closeModal(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

  $('saveBtn').addEventListener('click', async () => {
    const lat = parseNum($('f_lat').value), lng = parseNum($('f_lng').value), msg = $('msg');
    if (!$('f_nama').value.trim() || isNaN(lat) || isNaN(lng)) {
      msg.className = 'msg err'; msg.textContent = 'Nama, latitude, dan longitude wajib diisi dengan benar.'; return;
    }
    const payload = {
      nama: $('f_nama').value.trim(), alamat: $('f_alamat').value.trim(),
      telepon: $('f_telepon').value.trim(), type: $('f_type').value,
      latitude: lat, longitude: lng
    };
    $('saveBtn').disabled = true; msg.className = 'msg'; msg.textContent = 'Menyimpan...';
    try {
      const res = await fetch(UPDATE_URL(editing.id), {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json', 'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify(payload)
      });
      if (!res.ok) {
        let detail = '';
        try {
          const j = await res.json();
          detail = j.errors ? Object.values(j.errors).flat().join(' ') : (j.message || '');
        } catch (e) {}
        throw new Error('Gagal menyimpan (kode ' + res.status + ')' + (detail ? ': ' + detail : ''));
      }
      Object.assign(editing, payload);
      drawMarker(editing);
      closeModal();
      focusBranch(editing.id);
    } catch (err) {
      msg.className = 'msg err'; msg.textContent = err.message;
    } finally { $('saveBtn').disabled = false; }
  });

  // ---------- Jarak Haversine ----------
  function jarakKm(lat1, lon1, lat2, lon2) {
    const R = 6371, rad = d => d * Math.PI / 180;
    const dLat = rad(lat2 - lat1), dLon = rad(lon2 - lon1);
    const a = Math.sin(dLat / 2) ** 2 + Math.cos(rad(lat1)) * Math.cos(rad(lat2)) * Math.sin(dLon / 2) ** 2;
    return 2 * R * Math.asin(Math.sqrt(a));
  }

  // ---------- Cabang terdekat ----------
  let userMarker = null;
  $('btn-near').addEventListener('click', () => {
    if (branches.length === 0) { alert('Belum ada data cabang.'); return; }
    if (!navigator.geolocation) { alert('Browser tidak mendukung lokasi.'); return; }

    navigator.geolocation.getCurrentPosition(pos => {
      const { latitude, longitude } = pos.coords;
      let best = null;
      branches.forEach(b => {
        const d = jarakKm(latitude, longitude, b.latitude, b.longitude);
        if (!best || d < best.d) best = { d, b };
      });

      if (userMarker) map.removeLayer(userMarker);
      userMarker = L.circleMarker([latitude, longitude], { radius: 8 }).addTo(map).bindPopup('Lokasi Anda');

      activeId = best.b.id;
      markers[best.b.id].bindPopup(`<b>${esc(best.b.nama)}</b><br>Jarak: ${best.d.toFixed(2)} km`).openPopup();
      map.fitBounds(L.latLngBounds([[latitude, longitude], [best.b.latitude, best.b.longitude]]).pad(0.3));
      renderList();
    }, () => alert('Lokasi tidak dapat diakses. Periksa izin browser.'));
  });
</script>
</body>
</html>