<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Layanan Non Aktif</title>
	<link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>">

	<!-- Bootstrap Core CSS -->
	<link href="<?php echo media_url() ?>/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo media_url() ?>/css/load-font-googleapis.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo media_url() ?>/css/font-awesome.min.css">


	<!-- Custom CSS -->
	<link href="<?php echo media_url() ?>/css/frontend-style.css" rel="stylesheet">
	<link href="<?php echo media_url() ?>/css/portal.css" rel="stylesheet">

</head>

<body>

	<!-- Home -->
	<section class="content-section d-flex align-items-center justify-content-center" style="min-height: 80vh;">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8 col-md-offset-2">
					<div class="card text-center" style="border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px; padding: 40px 20px; background: #fff;">
						<div style="font-size: 64px; color: #f0ad4e; margin-bottom: 20px;">
							<i class="fa fa-exclamation-circle"></i>
						</div>
						<h2 style="font-weight: 700; color: #333; margin-bottom: 15px;">Mohon Maaf</h2>
						<p style="font-size: 18px; color: #555; line-height: 1.6; margin-bottom: 15px;">
							Untuk sementara aplikasi <strong><?= $school['setting_value'] ?></strong> tidak dapat digunakan.
						</p>
						<p style="font-size: 15px; color: #777; line-height: 1.6; margin-bottom: 25px;">
							Segera penuhi kewajiban administrasi dengan pihak <strong>indoweb.id</strong> untuk pengaktifan aplikasi kembali.
						</p>
						<div style="font-size: 16px; color: #444;">
							Terima kasih 😊
						</div>
						<button class="btn btn-primary" style="margin-top: 20px;" onclick="window.open('<?= $whatsappUrl ?>', '_blank')">Perpanjang Langganan</button>
					</div>
				</div>
			</div>
		</div>
	</section>


</body>

</html>