<!DOCTYPE html>
<html>

<head>
	<title>E-Sekolah | Layanan Siswa</title>
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


</head>

<body style="background-color:;">
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
		$layanan = $this->uri->segment('1');
		
		if( $layanan == 'layanan_siswa'){
			$this->load->view('frontend/home_siswa');
		} elseif($layanan == 'layanan_guru'){			
			$this->load->view('frontend/home_guru');
		}else{					
			$this->load->view('frontend/home_publik');
		}
	?>
	<?php $this->load->view('frontend/footer') ?>

	<script src="<?php echo media_url() ?>/js/bootstrap.min.js"></script>
</body>


</html>