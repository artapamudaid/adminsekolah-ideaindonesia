<!DOCTYPE html>
<html>

<head>
    <title>Presensi Siswa</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="<?php echo media_url('ico/favicon.ico') ?>">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo media_url() ?>auth/auth.css">
    <link rel="stylesheet" href="<?php echo media_url() ?>css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo media_url() ?>/css/jquery.toast.css">
    <script src="<?php echo media_url() ?>/js/jquery.min.js"></script>
    <script src="<?php echo media_url() ?>js/jquery.toast.js"></script>

	<style>
        input[type="text"] {
			padding: 8px 12px;
			border: 1px solid #ccc;
			border-radius: 5px;
			margin-bottom: 20px;
		}
        .form-tabs {
            margin-top: 30px;
            margin-bottom: 0 !important;
            flex-wrap: wrap;
        }
        .form-group {
            margin-bottom: 20px;
        }

        .form-group #rfid, .form-group #jenis-absen {
            margin-top: 6px;
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

		.loading-spinner {
			display: none;
			margin: 20px auto;
		}

		.loading-spinner.active {
			display: block;
		}

		.form-tabs {
			margin-top: 30px;
			margin-bottom: 0 !important;
			flex-wrap: wrap;
		}

		#message {
			text-align: center;
		}

    </style>
</head>

<body class="presence-body">
    <div class="bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="presence-page">

        <!-- Navbar -->
        <div class="presence-navbar">
            <?php if (isset($setting_logo) && $setting_logo['setting_value'] != NULL): ?>
                <img src="<?php echo upload_url('school/' . $setting_logo['setting_value']) ?>" alt="Logo">
            <?php else: ?>
                <img src="<?php echo media_url('img/as-landscape-white.png') ?>" alt="Logo">
            <?php endif; ?>
            <div class="school-name"><?php echo $setting_school['setting_value'] ?></div>
            <div class="school-detail">
                <?php echo $setting_address['setting_value'] . ' ' . $setting_district['setting_value'] . ' - ' . $setting_city['setting_value'] ?>
                &nbsp;·&nbsp; Telp. <?php echo $setting_phone['setting_value'] ?>
            </div>

			 <!-- Tab Navigation -->
            <div class="form-tabs">
                <a href="<?= base_url('manage/auth/login') ?>" class="tab-link">Login</a>
                <a href="<?= base_url('student_presence') ?>" class="tab-link active">Presensi Siswa</a>
                <a href="<?= base_url('employee_presence') ?>" class="tab-link">Presensi Pegawai</a>
                <a href="<?= base_url('guru') ?>" class="tab-link">Guru</a>
            </div>
        </div>
		
        <!-- Datetime -->
        <div class="presence-datetime">
            <div class="date-display" id="date-display"></div>
            <div class="time-display" id="time-display"></div>
            <div class="time-label">
                <span>Jam</span>
                <span>Menit</span>
                <span>Detik</span>
            </div>
        </div>

        <!-- Content (loaded by CodeIgniter) -->
        <div class="presence-content">
            <?php $this->load->view($main) ?>
        </div>

    </div>

    <script src="<?php echo media_url() ?>/js/bootstrap.min.js"></script>

    <script>
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('date-display').textContent = now.toLocaleDateString('id-ID', options);
            document.getElementById('time-display').textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>

    <?php if ($this->session->flashdata('success')): ?>
        <script>
            $(document).ready(function () {
                $.toast({
                    heading: 'Berhasil',
                    text: '<?php echo $this->session->flashdata('success') ?>',
                    position: 'top-right',
                    loaderBg: '#3080bd',
                    icon: 'success',
                    hideAfter: 3500,
                    stack: 6
                });
            });
        </script>
    <?php endif; ?>

    <?php if ($this->session->flashdata('failed')): ?>
        <script>
            $(document).ready(function () {
                $.toast({
                    heading: 'Gagal',
                    text: '<?php echo $this->session->flashdata('failed') ?>',
                    position: 'top-right',
                    loaderBg: '#dc2626',
                    icon: 'error',
                    hideAfter: 3500,
                    stack: 6
                });
            });
        </script>
    <?php endif; ?>
</body>
</html>