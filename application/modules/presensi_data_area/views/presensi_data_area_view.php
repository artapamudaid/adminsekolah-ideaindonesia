<?php
if (isset($presensi_data_area)) {
	$inputNameValue = $presensi_data_area['nama_area'];
	$inputLongiValue = $presensi_data_area['longi'];
	$inputLatiValue = $presensi_data_area['lati'];
	$inputRemarkValue = $presensi_data_area['remark'];
	$inputCreatedbyValue = $presensi_data_area['created_by'];
	$inputCreateddateValue = $presensi_data_area['created_date'];
	$inputUpdateddateValue = $presensi_data_area['updated_date'];
	$inputUpdatedbyValue = $presensi_data_area['updated_by'];
} else {
	$inputNameValue = set_value('nama_area');
	$inputLongiValue = set_value('longi');
	$inputLatiValue = set_value('lati');
	$inputRemarkValue = set_value('remark');
	$inputCreatedbyValue = set_value('created_by');
	$inputCreateddateValue = set_value('created_date');
	$inputUpdateddateValue = set_value('updated_date');
	$inputUpdatedbyValue = set_value('updated_by');
}

$defaultLat = !empty($inputLatiValue) ? $inputLatiValue : '-6.175392';
$defaultLng = !empty($inputLongiValue) ? $inputLongiValue : '106.827153';
?>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
	.area-view-card {
		background: #ffffff;
		border-radius: 14px;
		border: 1px solid #e9ecef;
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
		margin-bottom: 24px;
		overflow: hidden;
	}
	.area-view-header {
		padding: 16px 20px;
		background: #ffffff;
		border-bottom: 1px solid #f1f3f5;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	.area-view-title {
		font-size: 15px;
		font-weight: 700;
		color: #1e293b;
		margin: 0;
		display: flex;
		align-items: center;
		gap: 8px;
	}
	.area-view-body {
		padding: 20px;
	}
	.detail-table-custom tr td:first-child {
		width: 140px;
		font-weight: 600;
		color: #64748b;
		border-top: 1px solid #f1f5f9;
		padding: 10px 12px;
	}
	.detail-table-custom tr td:last-child {
		color: #1e293b;
		font-weight: 600;
		border-top: 1px solid #f1f5f9;
		padding: 10px 12px;
	}
	#mapView {
		height: 380px;
		width: 100%;
		border-radius: 12px;
		border: 1px solid #cbd5e1;
	}
</style>

<div class="content-wrapper"> 
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1 style="font-weight: 700; color: #1e293b;">
			Detail Area Presensi
			<small style="color: #64748b; font-size: 14px;">Informasi Titik Koordinat & Wilayah</small>
		</h1>
		<ol class="breadcrumb" style="background: transparent;">
			<li><a href="<?php echo site_url('manage') ?>" style="color: #2575FC;"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?php echo site_url('manage/presensi_data_area') ?>">Area Presensi</a></li>
			<li class="active">Detail</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<!-- Detail Info -->
			<div class="col-md-5">
				<div class="area-view-card">
					<div class="area-view-header">
						<h3 class="area-view-title"><i class="fa fa-info-circle text-primary"></i> Data Area</h3>
						<a href="<?php echo site_url('manage/presensi_data_area/edit/' . $presensi_data_area['id_area']); ?>" class="btn btn-warning btn-xs" style="border-radius: 6px; font-weight: 600;">
							<i class="fa fa-edit"></i> Edit
						</a>
					</div>
					<div class="area-view-body" style="padding: 0;">
						<table class="table detail-table-custom" style="margin-bottom: 0;">
							<tr>
								<td>Nama Area</td>
								<td><span style="color: #2575FC; font-size: 15px;"><?php echo htmlspecialchars($inputNameValue) ?></span></td>
							</tr>
							<tr>
								<td>Latitude</td>
								<td><span class="label label-danger" style="font-family: monospace; font-size: 12px;"><?php echo htmlspecialchars($inputLatiValue) ?></span></td>
							</tr>
							<tr>
								<td>Longitude</td>
								<td><span class="label label-primary" style="background: #2575FC; font-family: monospace; font-size: 12px;"><?php echo htmlspecialchars($inputLongiValue) ?></span></td>
							</tr>
							<tr>
								<td>Keterangan</td>
								<td><?php echo nl2br(htmlspecialchars($inputRemarkValue ? $inputRemarkValue : '-')) ?></td>
							</tr>
							<tr>
								<td>Waktu Dibuat</td>
								<td><small class="text-muted"><?php echo pretty_date($inputCreateddateValue, 'd F Y H:i', false) ?></small></td>
							</tr>
							<tr>
								<td>Pembaruan Terakhir</td>
								<td><small class="text-muted"><?php echo !empty($inputUpdateddateValue) ? pretty_date($inputUpdateddateValue, 'd F Y H:i', false) : '-' ?></small></td>
							</tr>
						</table>
					</div>
					<div style="padding: 14px 20px; background: #f8fafc; border-top: 1px solid #f1f3f5; display: flex; justify-content: space-between; align-items: center;">
						<a href="<?php echo site_url('manage/presensi_data_area'); ?>" class="btn btn-default" style="border-radius: 8px;">
							<i class="fa fa-arrow-left"></i> Kembali
						</a>
						<a href="https://www.google.com/maps?q=<?php echo $inputLatiValue . ',' . $inputLongiValue; ?>" target="_blank" class="btn btn-primary" style="background: #2575FC; border-color: #2575FC; border-radius: 8px;">
							<i class="fa fa-external-link"></i> Buka di Google Maps
						</a>
					</div>
				</div>
			</div>

			<!-- Map Preview -->
			<div class="col-md-7">
				<div class="area-view-card">
					<div class="area-view-header">
						<h3 class="area-view-title"><i class="fa fa-map-marker text-danger"></i> Posisi Marker Peta</h3>
					</div>
					<div class="area-view-body">
						<div id="mapView"></div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		var lat = parseFloat("<?php echo $defaultLat; ?>");
		var lng = parseFloat("<?php echo $defaultLng; ?>");

		var map = L.map('mapView', {
			center: [lat, lng],
			zoom: 16
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
		marker.bindPopup("<b><?php echo addslashes($inputNameValue); ?></b><br>Lat: " + lat + "<br>Lng: " + lng).openPopup();
	});
</script>