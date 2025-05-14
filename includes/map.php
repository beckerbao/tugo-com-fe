<!-- Thêm Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!-- Button và bản đồ -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
<h2 class="text-xl font-bold text-gray-900">Lịch trình chi tiết</h2>
<button id="btn-map" class="px-4 py-2 bg-purple-700 text-white rounded hover:bg-purple-800 text-sm md:text-base">
    Bảng đồ hành trình
</button>
</div>
<p class="text-sm italic text-red-500 mb-4">(tuỳ theo ngày khởi hành có thể sẽ khác nhau)</p>

<!-- Popup OSM -->
<div id="map-popup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center;">
<div id="map-popup-inner" style="position: relative; width: 90%; max-width: 800px; height: 80%; background: white; border-radius: 8px; overflow: hidden;">
    <button id="close-map" style="position: absolute; top: 10px; right: 10px; background: red; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer;">×</button>
    <div id="osm-map" style="width: 100%; height: 100%;"></div>
</div>
</div>

<script>
document.getElementById('btn-map').addEventListener('click', function() {
    document.getElementById('map-popup').style.display = 'flex';
    setTimeout(initMap, 100);
});

document.getElementById('close-map').addEventListener('click', function() {
    document.getElementById('map-popup').style.display = 'none';
});

// Đóng popup khi click ra ngoài vùng bản đồ
document.getElementById('map-popup').addEventListener('click', function(event) {
    if (event.target === this) {
    this.style.display = 'none';
    }
});

// let mapInitialized = false;
// function initMap() {
//   if (mapInitialized) return;
//   const map = L.map('osm-map').setView([33.8698439, 151.2082848], 5);
//   L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     attribution: '&copy; OpenStreetMap contributors'
//   }).addTo(map);

//   const points = [
//     {
//         "lat": "-33.8698439",
//         "lng": "151.2082848",
//         "name": "Sydney"
//     },
//     {
//         "lat": "-33.8571981",
//         "lng": "151.2151234",
//         "name": "Sydney Opera House"
//     }
//   ];

//   points.forEach(point => {
//     L.marker([point.lat, point.lng])
//       .addTo(map)
//       .bindPopup(point.name)
//       .openPopup();
//   });

//   mapInitialized = true;
// }

</script>

<script>
let mapInitialized = false;

function initMapWithPoints(points) {
if (mapInitialized) return;
if (!points.length) return;

const center = [parseFloat(points[0].lat), parseFloat(points[0].lng)];
const map = L.map('osm-map').setView(center, 5);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

points.forEach(point => {
    if (point.lat && point.lng) {
    L.marker([parseFloat(point.lat), parseFloat(point.lng)])
        .addTo(map)
        .bindPopup(point.name);
    }
});

mapInitialized = true;
}
</script>
<?php

// $tour_id = 1062077421;

// Gọi API lấy danh sách địa điểm của tour
$response = APICaller::get('/flashsale/tours/locations', [
    'tour_id' => $tour_id,
]);

if (
    $response['status'] === 'success' &&
    isset($response['data']['locations']) &&
    is_array($response['data']['locations'])
) {
    $locations = $response['data']['locations'];
    echo "<script>\n";
    echo "const points = [\n";

    foreach ($locations as $location) {
        $name = addslashes($location['name']);
        $lat = $location['latitude'];
        $lng = $location['longitude'];

        echo "  { lat: \"$lat\", lng: \"$lng\", name: \"$name\" },\n";
    }

    echo "];\n";
    echo "initMapWithPoints(points);\n";
    echo "</script>\n";
} else {
    echo "<script>console.error('Không thể lấy danh sách địa điểm');</script>";
}
?>