<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
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
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header">
						<div class="row">
							<div class="col-6">
								<form method="post" action="<?php echo site_url('manage/registrasi/export_excel') ?>" target="_blank" class="form-inline" style="display: flex; flex-wrap: wrap; gap: 10px;">
									<div class="form-group mb-2">
										<div class="input-group date" data-date="" data-date-format="yyyy-mm-dd">
											<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
											<input class="form-control" type="text" name="dari_tanggal" id="dari_tanggal" readonly="readonly" placeholder="Dari Tanggal" required="">
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="input-group date" data-date="" data-date-format="yyyy-mm-dd">
											<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
											<input class="form-control" type="text" name="sampai_tanggal" id="sampai_tanggal" readonly="readonly" placeholder="Sampai Tanggal" required="">
										</div>
									</div>
									<div class="form-group mb-2">
										<button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export</button>
									</div>
								</form>
							</div>
							<div class="col-6 pull-right">
								<form method="get" action="<?php echo site_url('manage/registrasi'); ?>" class="form-inline" style="display: flex; gap: 10px; justify-content: flex-end;">
									<div class="form-group mb-2">
										<input type="text" name="q" class="form-control" placeholder="Cari nama, sekolah, email..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
									</div>
									<div class="form-group mb-2">
										<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
									</div>
									<?php if (!empty($_GET['q'])): ?>
										<div class="form-group mb-2">
											<a href="<?php echo site_url('manage/registrasi'); ?>" class="btn btn-default"><i class="fa fa-times"></i> Reset</a>
										</div>
									<?php endif; ?>
								</form>
							</div>
						</div>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th>No</th>
										<th>Nama</th>
										<th>Nama Sekolah</th>
										<th>Alamat</th>
										<th>Kota</th>
										<th>Provinsi</th>
										<th>Sumber</th>
										<th>Whatsapp</th>
										<th>Email</th>
										<th>Password</th>
										<th>Tanggal</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$page = $this->uri->segment('4');
									$per_page = isset($per_page) ? $per_page : 10;
									$i = ($page) ? ($page + 1) : 1;

									if (!empty($registrasi_list)) {
										foreach ($registrasi_list as $row):
											?>
											<tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $row['registrasi_name']; ?></td>
												<td><?php echo $row['registrasi_school']; ?></td>
												<td><?php echo $row['registrasi_address']; ?></td>
												<td><?php echo $row['city_name']; ?></td>
												<td><?php echo $row['province_name']; ?></td>
												<td><?php echo $row['registrasi_sumber']; ?></td>
												<td><?php echo str_replace('+62', '0', $row['registrasi_phone']); ?></td>
												<td><?php echo $row['registrasi_email']; ?></td>
												<td><?php echo $row['registrasi_password']; ?></td>
												<td><?php echo pretty_date($row['registrasi_date'], 'd-m-Y', false); ?></td>
												<td>
													<a href="<?php echo site_url('manage/registrasi/rpw/' . $row['registrasi_id']) ?>" class="btn btn-xs btn-warning" onclick="return(confirm('Anda yakin akan mereset password a/n <?php echo $row['registrasi_name'] ?> ?'))"><i class="fa fa-lock" data-toggle="tooltip" title="Reset Password"></i></a>
													<a href="<?php echo site_url('manage/registrasi/send_wa/' . $row['registrasi_id']) ?>" class="btn btn-xs btn-success" onclick="return(confirm('Anda yakin akan mengirim wa username & password kepada <?php echo $row['registrasi_name'] ?> ?'))"><i class="fa fa-whatsapp" data-toggle="tooltip" title="Kirim WA"></i></a>
													<button class="btn btn-xs btn-default" onclick="copy_to_clipboard('<?php echo $row['registrasi_id']; ?>')"><i class="fa fa-copy" data-toggle="tooltip" title="Salin Redaksi"></i></button>
												</td>
											</tr>
											<?php
											$i++;
										endforeach;
									} else {
										?>
										<tr>
											<td colspan="12" align="center">Data Kosong</td>
										</tr>
										<?php } ?>
								</tbody>
							</table>

						</div>
						<?php if (isset($pagination)) { ?>
							<div class="text-center">
								<?php echo $pagination; ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
</div>

<script>
	function copy_to_clipboard(id) {
		$.post('<?php echo site_url('manage/registrasi/copy_msg'); ?>', { id: id }, function(response) {
			if (response.status === 'success' && response.message) {
				// Buat elemen textarea sementara
				const tempTextarea = document.createElement("textarea");
				tempTextarea.value = response.message;

				// Hindari gangguan dari CSS
				tempTextarea.style.position = "fixed";
				tempTextarea.style.top = "-1000px";
				tempTextarea.style.left = "-1000px";

				document.body.appendChild(tempTextarea);
				tempTextarea.focus();
				tempTextarea.select();

				// Coba salin ke clipboard
				try {
					const successful = document.execCommand("copy");
					if (successful) {
						console.log('Pesan berhasil disalin ke clipboard.');
					} else {
						alert('Perintah salin gagal dijalankan.');
					}
				} catch (err) {
					alert('Terjadi kesalahan saat menyalin ke clipboard.');
				}

				// Hapus elemen setelah selesai
				document.body.removeChild(tempTextarea);
			} else {
				alert(response.message || 'Gagal mengambil pesan.');
			}
		}, 'json');
	}
</script>
