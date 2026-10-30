<?php
if (isset($presensi_data_area)) {
	$inputNameValue = $presensi_data_area['nama_area'];
	$inputLongiValue = $presensi_data_area['longi'];
	$inputLatiValue = $presensi_data_area['lati'];
	$inputRemarkValue = $presensi_data_area['remark'];
} else {
	$inputNameValue = set_value('nama_area');
	$inputLongiValue = set_value('longi');
	$inputLatiValue = set_value('lati');
	$inputRemarkValue = set_value('remark');
}

// Default fallback koordinat jika kosong (Monas / Pusat Jakarta)
$defaultLat = !empty($inputLatiValue) ? $inputLatiValue : '-6.175392';
$defaultLng = !empty($inputLongiValue) ? $inputLongiValue : '106.827153';
$hasInitialCoord = (!empty($inputLatiValue) && !empty($inputLongiValue)) ? 'true' : 'false';
?>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
	.area-form-card {
		background: #ffffff;
		border-radius: 14px;
		border: 1px solid #e9ecef;
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
		margin-bottom: 24px;
		overflow: hidden;
	}
	.area-card-header {
		padding: 16px 20px;
		background: #ffffff;
		border-bottom: 1px solid #f1f3f5;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	.area-card-title {
		font-size: 15px;
		font-weight: 700;
		color: #1e293b;
		margin: 0;
		display: flex;
		align-items: center;
		gap: 8px;
	}
	.area-card-title i {
		color: #2575FC;
	}
	.area-card-body {
		padding: 20px;
	}

	.map-wrapper {
		position: relative;
		border-radius: 12px;
		overflow: hidden;
		border: 1px solid #cbd5e1;
		box-shadow: 0 2px 10px rgba(0,0,0,0.06);
	}
	#mapPicker {
		height: 420px;
		width: 100%;
		z-index: 1;
	}
	.map-search-bar {
		position: absolute;
		top: 12px;
		left: 12px;
		right: 12px;
		z-index: 999;
		background: #ffffff;
		border-radius: 10px;
		box-shadow: 0 4px 15px rgba(0,0,0,0.15);
		padding: 6px;
		display: flex;
		gap: 6px;
	}
	.map-search-input {
		border: none;
		outline: none;
		width: 100%;
		padding: 6px 12px;
		font-size: 13px;
	}
	.btn-map-search {
		background: #2575FC;
		color: #ffffff;
		border: none;
		border-radius: 6px;
		padding: 6px 14px;
		font-size: 13px;
		font-weight: 600;
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		transition: background 0.2s ease;
		white-space: nowrap;
	}
	.btn-map-search:hover {
		background: #1a5ac9;
		color: #ffffff;
	}
	.map-helper-toolbar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
		gap: 10px;
		margin-bottom: 12px;
	}
	.coord-badge-box {
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		padding: 8px 14px;
		font-size: 12px;
		color: #334155;
		display: flex;
		align-items: center;
		gap: 12px;
		flex-wrap: wrap;
	}
	.coord-badge-val {
		font-weight: 700;
		color: #2575FC;
		font-family: monospace;
		font-size: 13px;
	}
	.btn-gps-locate {
		background: #ecfdf5;
		color: #059669;
		border: 1px solid #a7f3d0;
		border-radius: 8px;
		padding: 6px 12px;
		font-size: 12px;
		font-weight: 600;
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		gap: 6px;
		transition: all 0.2s ease;
	}
	.btn-gps-locate:hover {
		background: #059669;
		color: #ffffff;
	}
	.form-control-modern {
		border-radius: 8px;
		border: 1px solid #cbd5e1;
		padding: 8px 12px;
		height: 40px;
		font-size: 13px;
		transition: border-color 0.2s ease;
	}
	.form-control-modern:focus {
		border-color: #2575FC;
		box-shadow: 0 0 0 3px rgba(37, 117, 252, 0.15);
	}
	.btn-modern-primary {
		background: #2575FC;
		color: #ffffff;
		border: none;
		border-radius: 8px;
		padding: 9px 20px;
		font-weight: 600;
		font-size: 14px;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		transition: background 0.2s ease;
	}
	.btn-modern-primary:hover {
		background: #1a5ac9;
		color: #ffffff;
	}
	.btn-modern-default {
		background: #f1f5f9;
		color: #475569;
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		padding: 9px 20px;
		font-weight: 600;
		font-size: 14px;
		transition: background 0.2s ease;
	}
	.btn-modern-default:hover {
		background: #e2e8f0;
		color: #1e293b;
	}
</style>

<div class="content-wrapper"> 
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1 style="font-weight: 700; color: #1e293b;">
			<?php echo isset($title) ? $title : 'Data Area Presensi'; ?>
			<small style="color: #64748b; font-size: 14px;">Penetapan Titik Koordinat Lokasi Presensi</small>
		</h1>
		<ol class="breadcrumb" style="background: transparent;">
			<li><a href="<?php echo site_url('manage') ?>" style="color: #2575FC;"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/presensi_data_area') ?>">Area Presensi</a></li>
			<li class="active"><?php echo $operation; ?></li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<?php echo form_open_multipart(current_url(), array('id' => 'formAreaPresensi')); ?>
		<?php if (isset($presensi_data_area)) { ?>
			<input type="hidden" name="id_area" value="<?php echo $presensi_data_area['id_area']; ?>">
		<?php } ?>

		<div class="row">
			<!-- Kolom Kiri: Form Input -->
			<div class="col-md-5">
				<div class="area-form-card">
					<div class="area-card-header">
						<h3 class="area-card-title"><i class="fa fa-pencil-square-o"></i> Informasi Area Presensi</h3>
					</div>
					<div class="area-card-body">
						<?php echo validation_errors(); ?>

						<div class="form-group">
							<label style="font-weight: 600; color: #334155;">Nama Area <span class="text-danger">*</span></label>
							<input name="nama_area" id="nama_area" type="text" class="form-control form-control-modern" value="<?php echo $inputNameValue ?>" placeholder="Contoh: Gedung Utama, Kampus A, Asrama" required>
							<small class="text-muted">Beri nama unik untuk mengenali lokasi presensi ini.</small>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label style="font-weight: 600; color: #334155;">
										<i class="fa fa-map-pin" style="color: #ef4444;"></i> Latitude (Lati) <span class="text-danger">*</span>
									</label>
									<input name="lati" id="inputLati" type="text" class="form-control form-control-modern" value="<?php echo $inputLatiValue ?>" placeholder="-6.2088..." required>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label style="font-weight: 600; color: #334155;">
										<i class="fa fa-map-pin" style="color: #2575FC;"></i> Longitude (Longi) <span class="text-danger">*</span>
									</label>
									<input name="longi" id="inputLongi" type="text" class="form-control form-control-modern" value="<?php echo $inputLongiValue ?>" placeholder="106.8456..." required>
								</div>
							</div>
						</div>

						<div class="alert alert-info" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; border-radius: 8px; font-size: 12px; margin-bottom: 16px;">
							<i class="fa fa-info-circle"></i> <strong>Tips Praktis:</strong> Anda tidak perlu mengetik koordinat manual. Cukup klik pada peta di sebelah kanan atau geser penanda marker map ke titik sekolah/kantor.
						</div>

						<div class="form-group">
							<label style="font-weight: 600; color: #334155;">Keterangan / Alamat Detail</label>
							<textarea name="remark" class="form-control form-control-modern" rows="3" placeholder="Masukkan alamat lengkap atau catatan area presensi..."><?php echo $inputRemarkValue ?></textarea>
						</div>

						<div style="margin-top: 24px; display: flex; gap: 10px;">
							<button type="submit" class="btn-modern-primary"><i class="fa fa-check"></i> Simpan Data Area</button>
							<a href="<?php echo site_url('manage/presensi_data_area'); ?>" class="btn-modern-default">Batal</a>
						</div>
					</div>
				</div>
			</div>

			<!-- Kolom Kanan: Peta Interaktif & Marker Picker -->
			<div class="col-md-7">
				<div class="area-form-card">
					<div class="area-card-header">
						<h3 class="area-card-title"><i class="fa fa-globe"></i> Penanda Peta (Map Marker Picker)</h3>
						<span class="label label-primary" style="background: #2575FC; border-radius: 6px; padding: 4px 8px; font-size: 11px;">Interaktif</span>
					</div>
					<div class="area-card-body">
						<!-- Toolbar Pembantu -->
						<div class="map-helper-toolbar">
							<div class="coord-badge-box">
								<span><i class="fa fa-crosshairs text-danger"></i> Lat: <span class="coord-badge-val" id="badgeLat"><?php echo !empty($inputLatiValue) ? $inputLatiValue : '-'; ?></span></span>
								<span><i class="fa fa-crosshairs text-primary"></i> Lng: <span class="coord-badge-val" id="badgeLng"><?php echo !empty($inputLongiValue) ? $inputLongiValue : '-'; ?></span></span>
							</div>

							<div style="display: flex; gap: 8px;">
								<button type="button" class="btn-gps-locate" id="btnGetLocation" title="Gunakan GPS perangkat saat ini">
									<i class="fa fa-location-arrow"></i> Lokasi Saya
								</button>
								<a href="#" target="_blank" id="btnGoogleMapsView" class="btn-modern-default" style="padding: 6px 12px; font-size: 12px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
									<i class="fa fa-external-link"></i> Google Maps
								</a>
							</div>
						</div>

						<!-- Map Container -->
						<div class="map-wrapper">
							<!-- Search Box di atas Map -->
							<div class="map-search-bar">
								<input type="text" id="mapSearchQuery" class="map-search-input" placeholder="Ketik nama jalan, kelurahan, sekolah, atau kota...">
								<button type="button" id="btnSearchPlace" class="btn-map-search">
									<i class="fa fa-search"></i> Cari
								</button>
							</div>

							<!-- Kontainer Leaflet Peta -->
							<div id="mapPicker"></div>
						</div>

						<p style="margin-top: 10px; font-size: 12px; color: #64748b;">
							<i class="fa fa-hand-pointer-o" style="color: #2575FC;"></i> 
							<strong>Cara Pakai:</strong> Klik sembarang tempat pada peta untuk meletakkan marker, atau <strong>drag & drop (geser)</strong> penanda marker merah ke titik presensi yang tepat.
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</section>
</div>

<!-- Script Inisialisasi Peta & Marker -->
<script type="text/javascript">
	$(document).ready(function() {
		var initialLat = parseFloat("<?php echo $defaultLat; ?>");
		var initialLng = parseFloat("<?php echo $defaultLng; ?>");
		var hasInitial = <?php echo $hasInitialCoord; ?>;
		var defaultZoom = hasInitial ? 16 : 13;

		// 1. Inisialisasi Leaflet Map
		var map = L.map('mapPicker', {
			center: [initialLat, initialLng],
			zoom: defaultZoom,
			zoomControl: true
		});

		// Tile Layer OpenStreetMap
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
			maxZoom: 19
		}).addTo(map);

		// Custom Marker Icon yang modern
		var customIcon = L.icon({
			iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
			shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
			iconSize: [25, 41],
			iconAnchor: [12, 41],
			popupAnchor: [1, -34],
			shadowSize: [41, 41]
		});

		// 2. Buat Marker Draggable
		var marker = L.marker([initialLat, initialLng], {
			draggable: true,
			icon: customIcon
		}).addTo(map);

		marker.bindPopup("<b>Titik Area Presensi</b><br>Geser untuk mengubah lokasi.").openPopup();

		// Update koordinat ke form input & badge
		function updateCoordinates(lat, lng) {
			var formattedLat = lat.toFixed(7);
			var formattedLng = lng.toFixed(7);

			$('#inputLati').val(formattedLat);
			$('#inputLongi').val(formattedLng);
			$('#badgeLat').text(formattedLat);
			$('#badgeLng').text(formattedLng);

			// Update link Google Maps
			$('#btnGoogleMapsView').attr('href', 'https://www.google.com/maps?q=' + formattedLat + ',' + formattedLng);
		}

		// Update saat marker digeser (dragend)
		marker.on('dragend', function(e) {
			var position = marker.getLatLng();
			updateCoordinates(position.lat, position.lng);
			marker.setPopupContent("<b>Titik Area Presensi</b><br>Lat: " + position.lat.toFixed(6) + "<br>Lng: " + position.lng.toFixed(6)).openPopup();
		});

		// Update saat peta diklik (click)
		map.on('click', function(e) {
			var lat = e.latlng.lat;
			var lng = e.latlng.lng;
			marker.setLatLng([lat, lng]);
			updateCoordinates(lat, lng);
			marker.setPopupContent("<b>Titik Area Presensi</b><br>Lat: " + lat.toFixed(6) + "<br>Lng: " + lng.toFixed(6)).openPopup();
		});

		// Jika awal belum ada koordinat, minta user atau isi default
		if (hasInitial) {
			updateCoordinates(initialLat, initialLng);
		}

		// 3. Reverse Sync: Saat user mengubah input text Lati atau Longi secara manual
		function syncInputToMap() {
			var manualLat = parseFloat($('#inputLati').val());
			var manualLng = parseFloat($('#inputLongi').val());

			if (!isNaN(manualLat) && !isNaN(manualLng) && manualLat >= -90 && manualLat <= 90 && manualLng >= -180 && manualLng <= 180) {
				marker.setLatLng([manualLat, manualLng]);
				map.setView([manualLat, manualLng], 16);
				$('#badgeLat').text(manualLat.toFixed(7));
				$('#badgeLng').text(manualLng.toFixed(7));
				$('#btnGoogleMapsView').attr('href', 'https://www.google.com/maps?q=' + manualLat + ',' + manualLng);
			}
		}

		$('#inputLati, #inputLongi').on('change blur', function() {
			syncInputToMap();
		});

		// 4. Tombol Lokasi Saya (HTML5 Geolocation)
		$('#btnGetLocation').click(function() {
			var $btn = $(this);
			if (navigator.geolocation) {
				$btn.html('<i class="fa fa-spinner fa-spin"></i> Mendeteksi...');
				navigator.geolocation.getCurrentPosition(
					function(position) {
						var userLat = position.coords.latitude;
						var userLng = position.coords.longitude;

						marker.setLatLng([userLat, userLng]);
						map.setView([userLat, userLng], 17);
						updateCoordinates(userLat, userLng);
						marker.setPopupContent("<b>Lokasi Perangkat Anda</b><br>Akurasi: ±" + Math.round(position.coords.accuracy) + " meter").openPopup();

						$btn.html('<i class="fa fa-check text-success"></i> Lokasi Ditemukan!');
						setTimeout(function() {
							$btn.html('<i class="fa fa-location-arrow"></i> Lokasi Saya');
						}, 3000);
					},
					function(error) {
						var errorMsg = "Tidak dapat mendeteksi lokasi: ";
						switch(error.code) {
							case error.PERMISSION_DENIED:
								errorMsg += "Izin akses lokasi ditolak.";
								break;
							case error.POSITION_UNAVAILABLE:
								errorMsg += "Informasi lokasi tidak tersedia.";
								break;
							case error.TIMEOUT:
								errorMsg += "Waktu permintaan lokasi habis.";
								break;
							default:
								errorMsg += "Terjadi kesalahan tidak dikenal.";
						}
						alert(errorMsg);
						$btn.html('<i class="fa fa-location-arrow"></i> Lokasi Saya');
					},
					{ enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
				);
			} else {
				alert("Browser Anda tidak mendukung deteksi lokasi (Geolocation).");
			}
		});

		// 5. Fitur Pencarian Tempat (OpenStreetMap Nominatim Geocoding)
		function searchPlace() {
			var query = $('#mapSearchQuery').val();
			if (!query || query.trim() === '') {
				alert('Silakan ketik nama tempat atau alamat yang ingin dicari.');
				return;
			}

			var $btn = $('#btnSearchPlace');
			$btn.html('<i class="fa fa-spinner fa-spin"></i> Mencari...').prop('disabled', true);

			$.ajax({
				url: 'https://nominatim.openstreetmap.org/search',
				method: 'GET',
				data: {
					q: query,
					format: 'json',
					addressdetails: 1,
					limit: 1
				},
				dataType: 'json',
				success: function(data) {
					if (data && data.length > 0) {
						var result = data[0];
						var searchLat = parseFloat(result.lat);
						var searchLng = parseFloat(result.lon);

						marker.setLatLng([searchLat, searchLng]);
						map.setView([searchLat, searchLng], 17);
						updateCoordinates(searchLat, searchLng);

						marker.setPopupContent("<b>Hasil Pencarian:</b><br>" + result.display_name).openPopup();
					} else {
						alert('Tempat "' + query + '" tidak ditemukan. Coba gunakan kata kunci jalan atau kota yang lebih umum.');
					}
				},
				error: function() {
					alert('Gagal menghubungi server pencarian lokasi. Periksa koneksi internet Anda.');
				},
				complete: function() {
					$btn.html('<i class="fa fa-search"></i> Cari').prop('disabled', false);
				}
			});
		}

		$('#btnSearchPlace').click(function(e) {
			e.preventDefault();
			searchPlace();
		});

		$('#mapSearchQuery').on('keypress', function(e) {
			if (e.which === 13) {
				e.preventDefault();
				searchPlace();
			}
		});

		// Trigger resize map jika modal atau tab dimuat
		setTimeout(function() {
			map.invalidateSize();
		}, 300);
	});
</script>