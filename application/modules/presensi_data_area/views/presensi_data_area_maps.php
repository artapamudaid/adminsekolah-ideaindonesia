<?php
$id_area = $presensi_data_area["id_area"] ?? '';
$lati = $presensi_data_area["lati"] ?? '-6.175392';
$longi = $presensi_data_area["longi"] ?? '106.827153';
$nama = $presensi_data_area["nama_area"] ?? 'Area Presensi';
$remark = $presensi_data_area["remark"] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
  <title>Peta Area Presensi - <?php echo htmlspecialchars($nama); ?></title>
  <link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>">
  
  <!-- Leaflet CSS & JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }
    #map {
      height: 100%;
      width: 100%;
    }
    .info-floating-card {
      position: absolute;
      top: 16px;
      left: 16px;
      z-index: 1000;
      background: #ffffff;
      border-radius: 12px;
      padding: 14px 18px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
      max-width: 360px;
    }
    .info-title {
      margin: 0 0 6px 0;
      font-size: 15px;
      font-weight: 700;
      color: #1e293b;
    }
    .info-coord {
      font-size: 12px;
      font-family: monospace;
      color: #64748b;
      margin-bottom: 8px;
    }
    .btn-gmaps {
      display: inline-block;
      background: #2575FC;
      color: #ffffff;
      text-decoration: none;
      font-size: 12px;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 6px;
      transition: background 0.2s ease;
    }
    .btn-gmaps:hover {
      background: #1a5ac9;
    }
  </style>
</head>
<body>
  <div class="info-floating-card">
    <h4 class="info-title">📍 <?php echo htmlspecialchars($nama); ?></h4>
    <div class="info-coord">Lat: <?php echo htmlspecialchars($lati); ?> | Lng: <?php echo htmlspecialchars($longi); ?></div>
    <?php if (!empty($remark)) { ?>
      <p style="font-size: 12px; color: #475569; margin: 0 0 8px 0;"><?php echo htmlspecialchars($remark); ?></p>
    <?php } ?>
    <a href="https://www.google.com/maps?q=<?php echo $lati . ',' . $longi; ?>" target="_blank" class="btn-gmaps">
      Buka di Google Maps &rarr;
    </a>
  </div>

  <div id="map"></div>

  <script>
    var lat = parseFloat("<?php echo $lati; ?>");
    var lng = parseFloat("<?php echo $longi; ?>");

    var map = L.map('map', {
      center: [lat, lng],
      zoom: 17
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var customIcon = L.icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41]
    });

    var marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
    marker.bindPopup("<b><?php echo addslashes($nama); ?></b><br>Lat: " + lat + "<br>Lng: " + lng).openPopup();
  </script>
</body>
</html>