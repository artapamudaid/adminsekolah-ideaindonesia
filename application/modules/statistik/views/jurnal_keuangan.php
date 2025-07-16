<style>
	/* Styling untuk indikator loading */
	#cetak-jurnal {
		display: none;
	}
</style>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
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
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">

						<!-- <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#printStudent"><i class="fa fa-print"></i> Cetak</button>
						<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#xlsStudent"><i class="fa fa-excel"></i> Export Xls</button> -->
						<div class="row">
							<div class="col-md-9"><br>
								<table>
									<tr>
										<td>
											<select style="width: 200px;" id="majors_id" name="m" class="form-control" required>
												<option value="">--- Pilih Unit Sekolah ---</option>
												<?php foreach ($majors as $row) { ?>
													<option value="<?php echo $row['majors_id']; ?>" <?php echo (isset($s['m']) && $s['m'] == $row['majors_id']) ? 'selected' : '' ?>><?php echo $row['majors_short_name'] ?></option>
												<?php } ?>
											</select>
										</td>

										<td>
											&nbsp&nbsp
										</td>

										<td>
											<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
												<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
												<input class="form-control" type="text" name="ds" id="ds" readonly="readonly" placeholder="Tanggal Awal">
											</div>
										</td>

										<td>
											&nbsp&nbsp
										</td>
										<td>
											<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
												<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
												<input class="form-control" type="text" name="de" id="de" readonly="readonly" placeholder="Tanggal Akhir">
											</div>
										</td>
										<td>
											&nbsp&nbsp
										</td>
										<td>
											&nbsp&nbsp
										</td>
										<td>
											&nbsp&nbsp
										</td>
										<td>
											<button id="searchJurnal" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<br>
						<!-- /.box-header -->
						<div id="div_show_data">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Jurnal <span id="tglJurnal"></span> </h3>
								</div>
								<div class="box-body table-responsive">
									<table class="table table-responsive table-hover table-bordered" id="xtable" style="white-space: nowrap;">
										<thead>
											<tr>
												<th>Jenis Pembayaran</th>
												<th>Penerimaan</th>
												<th>Pengeluaran</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
										<tfoot>
											<tr style="background-color: #E2F7FF;">
												<td align="right"><strong>Sub Total</strong></td>
												<td id="total-penerimaan">Rp 0</td>
												<td class="total-pengeluaran">Rp 0</td>
											</tr>
											<tr style="background-color: #F0B2B2;">
												<td align="right"><strong>Saldo Awal</strong></td>
												<td id="saldo-awal">Rp 0</td>
												<td>-</td>
											</tr>
											<tr style="background-color: #FFFCBE;">
												<td align="right"><strong>Total (Sub Total + Saldo Awal)</strong></td>
												<td id="total">Rp 0</td>
												<td class="total-pengeluaran">Rp 0</td>
											</tr>
											<tr style="background-color: #c2d2f6;">
												<td align="right"><strong>Saldo Akhir</strong></td>
												<td id="saldo-akhir">Rp 0</td>
												<td></td>
											</tr>
										</tfoot>
									</table>
								</div>
								<div class="box-footer" id="cetak-jurnal">
									<table class="table">
										<tr>
											<td>
												<div class="md-6">
													<a id="excel-rekap" class="btnCetak btn btn-success" data-judul="Rekap-Jurnal" data-type="xls" data-url=""><span class="fa fa-file-excel-o"></span> Export Excel
													</a>
												</div>
											</td>
											<td>
												<div class="pull-right">
													<!-- <a id="excel-rekap" class="btnCetak btn btn-success" data-judul="Rekap-Jurnal" data-type="xls" data-url=""><span class="fa fa-file-excel-o"></span> Excel Rekap Laporan
													</a> -->
												</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
					</div>

					<!-- /.box -->
				</div>
			</div>
			</form>
	</section>
	<!-- /.content -->
</div>

<div class="modal fade" id="printStudent" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Pilih Unit</h4>
			</div>
			<form action="<?php echo base_url(); ?>manage/student/print_students" method="post" target="_blank">
				<div class="modal-body">
					<label>Unit Sekolah</label>
					<select required="" name="modal_majors" id="modal_majors" class="form-control">
						<option value="">-Pilih Unit Sekolah-</option>
						<?php foreach ($majors as $row) { ?>
							<option value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
						<?php } ?>
					</select>
					<label>Tahun Ajaran</label>
					<div id="div_period">
						<select required="" name="modal_period" id="modal_period" class="form-control">
							<option value="">-Pilih Tahun Ajaran-</option>
						</select>
					</div>
					<label>Bulan</label>
					<div id="div_month">
						<select required="" name="modal_month" id="modal_month" class="form-control">
							<option value="">-Pilih Bulan-</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger"><i class="fa fa-print"></i> Cetak</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="xlsStudent" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Pilih Unit</h4>
			</div>
			<form action="<?php echo base_url(); ?>manage/student/excel_students" method="post" target="_blank">
				<div class="modal-body">
					<label>Unit Sekolah</label>
					<select required="" name="xls_majors" id="xls_majors" class="form-control">
						<option value="all">-Unit Sekolah-</option>
						<?php foreach ($majors as $row) { ?>
							<option value="<?php echo $row['majors_id'] ?>"><?php echo $row['majors_short_name'] ?></option>
						<?php } ?>
					</select>
					<label>Tahun Ajaran</label>
					<div id="div_ajaran_xls">
						<select required="" name="xls_ajaran" id="xls_ajaran" class="form-control">
							<option value="">-Pilih Tahun Ajaran-</option>
						</select>
					</div>
					<label>Bulan</label>
					<div id="div_month_xls">
						<select required="" name="xls_month" id="xls_month" class="form-control">
							<option value="">-Pilih Bulan-</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success"><i class="fa fa-excel-o"></i> Export</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	var unit = '';
	var tglMulai = '';
	var tglAkhir = '';

	// $(document).ready(function() {
	// 	$('#xtable').DataTable();
	// });

	$("#searchJurnal").click(function() {
		console.log("cari jurnal");
		cari_jurnal();
	});

	function cari_jurnal() {
		var ds = $("#ds").val();
		var de = $("#de").val();
		var majors_id = $("#majors_id").val();
		//alert(id_jurusan+id_kelas);
		if (ds != '' && de != '' && majors_id != '') {
			const baseURL = "<?php echo base_url(); ?>";
			$('#cetak-jurnal').show();
			$("#pdf-rekap").attr("href", baseURL + "manage/report/cetak_rekap_jurnum/" + ds + "/" + de + "/" + majors_id);
			$("#excel-rekap").attr("data-url", baseURL + "manage/report/excel_rekap_jurnal/" + ds + "/" + de + "/" + majors_id);

			var dateStart = new Date(ds);
			var dateEnd = new Date(de);

			var months = [
				'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
				'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
			];

			tglMulai = dateStart.getDate() + ' ' + months[dateStart.getMonth()] + ' ' + dateStart.getFullYear();
			tglAkhir = dateEnd.getDate() + ' ' + months[dateEnd.getMonth()] + ' ' + dateEnd.getFullYear();

			var selectUnit = document.getElementById('majors_id');
			unit = selectUnit.options[selectUnit.selectedIndex].text;
			$("#tglJurnal").html(unit + " " + tglMulai + " Sampai " + tglAkhir);

			var param = "";
			$("#xtable").DataTable({
				ordering: true,
				processing: true,
				serverSide: true,
				destroy: true, // Menghancurkan DataTable sebelumnya sebelum inisialisasi ulang
				language: {
					processing: "<img src='<?php echo base_url() ?>uploads/loading/loading.gif' style='width:50px; height:50px;' />"
				},
				ajax: {
					url: "<?php echo base_url('manage/statistik/search_report_jurnal'); ?>",
					type: 'POST',
					data: {
						param: param,
						ds: ds,
						de: de,
						majors_id: majors_id

					},
					dataSrc: function(json) {
						// console.log(json)
						$('#total-penerimaan').html("Rp " + numberFormat(json.totalPenerimaan));
						$('.total-pengeluaran').html("Rp " + numberFormat(json.totalPengeluaran));
						$('#saldo-awal').html("Rp " + numberFormat(json.saldoAwal));

						var total = parseFloat(json.saldoAwal) + parseFloat(json.totalPenerimaan);
						var saldoAkhir = total - parseFloat(json.totalPengeluaran);
						$('#total').html("Rp " + numberFormat(total));
						$('#saldo-akhir').html("Rp " + numberFormat(saldoAkhir));
						return json.data;
					}
				},
			});
		}
	}

	function numberFormat(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}

	$('.btnCetak').on('click', function() {
		var url = $(this).data('url');
		var type = $(this).data('type');
		var judul = $(this).data('judul');
		var namaFile = 'Laporan ' + judul + ' ' + unit + ' Tanggal ' + tglMulai + " Sampai " + tglAkhir + "." + type;

		console.log(url);
		fetch(url, {
				method: 'POST', // Menentukan metode POST
				headers: {
					'Content-Type': 'application/json',
				}
			})
			.then(response => response.blob())
			.then(blob => {
				const link = document.createElement('a');
				link.href = URL.createObjectURL(blob);
				link.download = namaFile;
				link.click();
			})
			.catch(error => console.error("Terjadi kesalahan: ", error));
	});
</script>