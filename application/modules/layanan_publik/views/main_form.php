<!DOCTYPE html>
<html>

<head>
	<title>E-Sekolah | Layanan Publik</title>
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

	<script src="<?php echo media_url() ?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



</head>

<body>
	<nav class="navbar navbar-default fixed" style="background-color:#5BBCFF;">
		<div class="container d-flex justify-content-center align-items-center">
			<a class="navbar-brand" href="#">
				<?php if (isset($setting_logo) AND $setting_logo['setting_value'] != NULL) { ?>
				<img src="<?php echo upload_url('school/' . $setting_logo['setting_value']) ?>" style="height: 40px; margin-top: -10px;">
				<?php } else { ?>
				<img src="<?php echo media_url('img/logo.svg') ?>" style="height: 40px; margin-top: -10px; margin-right: 5px;">
				<?php } ?>
			</a>
			<div class="text-center">
				<font size="4">
					<b>
						Admin Sekolah <?php echo $setting_school['setting_value'] ?>
					</b>
				</font>
				<br>
				<font size="2"><?php echo $setting_address['setting_value'].' '.$setting_district['setting_value'].' - '.$setting_city['setting_value'].'<br> Telp. '.$setting_phone['setting_value'] ?>
				</font>
			</div>
		</div>
	</nav>

	<?php 
		if($main == 'publik'){
			$this->load->view('layanan_publik/publik');
		}elseif($main == 'survei'){
			$this->load->view('layanan_publik/survei');
		}elseif($main == 'pengaduan'){
			$this->load->view('layanan_publik/pengaduan');
		}elseif($main == 'pencarian'){
			$this->load->view('layanan_publik/pencarian');
		}else{
			$this->load->view('layanan_publik/tiket');
		}
	 ?>
	<?php $this->load->view('frontend/footer') ?>

	<script src="<?php echo media_url() ?>/js/bootstrap.min.js"></script>
</body>


</html>