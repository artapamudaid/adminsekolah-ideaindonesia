<!DOCTYPE html>
<html>

<head>
	<title>Presensi Siswa</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>">
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="<?php echo media_url() ?>/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo media_url() ?>/css/AdminLTE.min.css">
	<link rel="stylesheet" href="<?php echo media_url() ?>/css/style.css">
	<link rel="stylesheet" href="<?php echo media_url() ?>/css/frontend-style.css">
	<link rel="stylesheet" href="<?php echo media_url() ?>/css/load-font-googleapis.css">
	<link rel="stylesheet" href="<?php echo media_url() ?>/css/jquery.toast.css">

	<script src="<?php echo media_url() ?>/js/jquery.min.js"></script>
	<script src="<?php echo media_url() ?>js/jquery.toast.js"></script>

	<style>
		body {
			margin: 0;
			padding: 0;
			height: 100vh;
			display: flex;
			justify-content: center;
			/* align-items: center; */
			background-color: #fff;
			font-family: Arial, sans-serif;
		}

		.container {
			text-align: center;
			/* padding: 20px; */
			/* border: 3px solid #2f4f4f; */
			border-radius: 5px;
			background-color: #fff;
		}

		input[type="text"] {
			padding: 8px 12px;
			border: 1px solid #ccc;
			border-radius: 5px;
			width: 200px;
			margin-bottom: 20px;
		}

		nav.navbar {
			max-width: 500px;
			margin: 0 auto;
			width: 100%;
			background-color: #3c8dbc;
		}

		.navbar-header {
			text-align: center;
		}

		.navbar-brand {
			float: none !important;
			display: inline-block;
		}

		section.content {
			width: 100%;
			max-width: 500px;
			padding: 20px;
			border-radius: 8px;
		}

		.box {
			margin: 0 auto;
		}

		#presensiForm {
			text-align: center;
		}

		.form-group {
			width: 100%;
		}

		.form-group select {
			width: 100%;
			max-width: 300px;
			margin: 0 auto;
		}

		.form-group input[type="text"] {
			width: 100%;
			max-width: 300px;
			margin: 0 auto;
		}

		#area-foto img {
			width: 200px;
			height: 200px;
			border-radius: 50%;
			object-fit: cover;
			border: 2px solid #fff;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
			margin-bottom: 10px;
		}

		#foto-santri {
			visibility: hidden;
		}

		#foto-santri.show {
			visibility: visible;
		}

		[data-letters] {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			width: 200px;
			height: 200px;
			border-radius: 50%;
			background: #00a65a;
			color: white;
			font-weight: bold;
			font-size: 100px;
			font-family: sans-serif;
		}

		[data-letters]::before {
			content: attr(data-letters);
		}
	</style>

</head>

<body>
	<div class="container">
		<nav class="navbar navbar-default">
			<div class="navbar-header text-center" style="width: 100%;">
				<a class="navbar-brand" href="#" style="display: inline-block; float: none;">
					<?php if (isset($setting_logo) and $setting_logo['setting_value'] != NULL) { ?>
						<img src="<?php echo upload_url('school/' . $setting_logo['setting_value']) ?>" style="height: 50px; margin-top: -10px;" />
					<?php } else { ?>
						<img src="<?php echo media_url('img/logo.svg') ?>" style="height: 50px; margin-top: -10px; margin-right: 5px;" />
					<?php } ?>
				</a>
				<div>
					<font size="4"><b><?php echo $setting_school['setting_value'] ?></b></font><br>
					<font size="2"><?php echo $setting_address['setting_value'] . ' ' . $setting_district['setting_value'] . ' - ' . $setting_city['setting_value'] . '<br> Telp. ' . $setting_phone['setting_value'] ?></font>
				</div>
			</div>
		</nav>


		<?php $this->load->view($main) ?>
	</div>


	<script src="<?php echo media_url() ?>/js/bootstrap.min.js"></script>
	<?php if ($this->session->flashdata('success')) { ?>
		<script>
			$(document).ready(function() {
				$.toast({
					heading: 'Berhasil',
					text: '<?php echo $this->session->flashdata('success') ?>',
					position: 'top-right',
					loaderBg: '#ff6849',
					icon: 'success',
					hideAfter: 3500,
					stack: 6
				})
			});
		</script>
	<?php } ?>

	<?php if ($this->session->flashdata('failed')) { ?>
		<script>
			$(document).ready(function() {
				$.toast({
					heading: 'Gagal',
					text: '<?php echo $this->session->flashdata('failed') ?>',
					position: 'top-right',
					loaderBg: '#ff2e2e',
					icon: 'error',
					hideAfter: 3500,
					stack: 6
				})
			});
		</script>
	<?php } ?>
</body>


</html>