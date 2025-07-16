<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<?php echo isset($title) ? '' . $title : null; ?>
			<small>List</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
			<li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
		</ol>
	</section>
	<section class="content">
		<a class="btn btn-default btn-sm mx-10" href="<?php echo site_url('manage/worksheet/alokasi/' . $this->uri->segment('4')) ?>"><span class="glyphicon glyphicon-repeat"></span> Kembali</a>
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="box">
					<div class="box-header">
						<h4 style="font-weight: bold;">Alokasi Anggaran</h4>
					</div>
					<div class="box-body">
						<canvas id="grafikAlokasi"></canvas>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="box">
					<div class="box-header">
						<h4 style="font-weight: bold;">Alokasi Per Standar</h4>
					</div>
					<div class="box-body">
						<canvas id="grafikPerStandar"></canvas>
					</div>
				</div>
			</div>
		</div>
	</section>

</div>

<script>
	// Data untuk grafik donat
	var data = {
		labels: ['Sudah Dialokasikan', 'Belum Dialokasikan'],
		datasets: [{
			data: [<?php foreach ($grafik_anggaran as $val) {
						echo $val . ",";
					} ?>],
			backgroundColor: ['#0d6582', '#36A2EB'],
		}]
	};

	// Pengaturan untuk grafik donat
	var options = {
		responsive: true,
		maintainAspectRatio: false,
		tooltips: {
			callbacks: {
				label: function(tooltipItem, data) {
					var dataset = data.datasets[tooltipItem.datasetIndex];
					var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
						return previousValue + currentValue;
					});
					var currentValue = dataset.data[tooltipItem.index];
					var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
					return percentage + "%";
				}
			}
		}
	};

	// Mendapatkan elemen canvas
	var ctx = document.getElementById('grafikAlokasi').getContext('2d');

	// Membuat dan menggambar grafik donat
	var grafikAlokasi = new Chart(ctx, {
		type: 'doughnut',
		data: data,
		options: options
	});
</script>

<script>
	// Data untuk grafik donat
	var data = {
		labels: [<?php foreach ($standar as $val) {
						echo "'" . $val . "',";
					} ?>],
		datasets: [{
			data: [<?php foreach ($nominal as $val) {
						echo  $val . ",";
					} ?>],
			backgroundColor: [<?php foreach ($color as $val) {
									echo "'" . $val . "',";
								} ?>],
		}]
	};

	// Pengaturan untuk grafik donat
	var options = {
		responsive: true,
		maintainAspectRatio: false,
		tooltips: {
			callbacks: {
				label: function(tooltipItem, data) {
					var dataset = data.datasets[tooltipItem.datasetIndex];
					var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
						return previousValue + currentValue;
					});
					var currentValue = dataset.data[tooltipItem.index];
					var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
					return percentage + "%";
				}
			}
		}
	};

	// Mendapatkan elemen canvas
	var ctx = document.getElementById('grafikPerStandar').getContext('2d');

	// Membuat dan menggambar grafik donat
	var grafikPerStandar = new Chart(ctx, {
		type: 'doughnut',
		data: data,
		options: options
	});
</script>