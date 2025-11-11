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
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header"> <br>
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="ds" id="ds" readonly="readonly" placeholder="Tanggal Awal">
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<div class="input-group date " data-date="" data-date-format="yyyy-mm-dd">
										<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										<input class="form-control" type="text" name="de" id="de" readonly="readonly" placeholder="Tanggal Akhir">
									</div>
								</div>
							</div>
							<!-- <div class="col-md-3">  
    							<div class="form-group">
            						<select class="form-control" name="periode" id="period_id" required="">
            								<option value="">--Pilih Tahun Ajaran--</option>
            								<?php foreach ($period as $row): ?>
            								<option value="<?php echo $row['period_id'] ?>"><?php echo $row['period_start'] . '/' . $row['period_end'] ?></option>
            							    <?php endforeach; ?>
    							    </select>
							    </div>
							</div> -->
							<div class="col-md-3">
								<div class="form-group">
									<select required="" name="majors_id" id="majors_id" class="form-control">
										<option value="">-- Pilih Unit Sekolah --</option>
										<?php if ($this->session->userdata('umajorsid') == '0') { ?>
											<option value="all">Semua Unit</option>
										<?php } ?>
										<?php foreach ($majors as $row) { ?>
											<option value="<?php echo $row['majors_id']; ?>"><?php echo $row['majors_short_name'] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<!--<div id="div_bank">
							<div class="col-md-2">  
								<div class="form-group">
        						<select required="" name="bank_id" id="bank_id" class="form-control">
        						    <option value="">-- Pilih Bank --</option>
        						</select>
								</div>
							</div>	
					        </div>-->
							<div>
								<button type="submit" class="btn btn-primary" onclick="cari_data()">Filter</button>
							</div>
						</div>
						<div id="div_show_data">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Jurnal <span id="tglJurnal"></span> </h3>
								</div>
								<div class="box-body table-responsive">
									<table class="table table-responsive table-hover table-bordered" id="xtable" style="white-space: nowrap;">
										<thead>
											<tr>
												<th>Akun Kas</th>
												<th>Tanggal</th>
												<th>Kode Akun</th>
												<th>Keterangan</th>
												<th>NIS</th>
												<th>Nama Siswa</th>
												<th>Kelas</th>
												<th>Penerimaan</th>
												<th>Pengeluaran</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
										<tfoot>
											<tr style="background-color: #E2F7FF;">
												<td colspan="7" align="right"><strong>Sub Total</strong></td>
												<td id="total-penerimaan">Rp 0</td>
												<td class="total-pengeluaran">Rp 0</td>
											</tr>
											<tr style="background-color: #F0B2B2;">
												<td colspan="7" align="right"><strong>Saldo Awal</strong></td>
												<td id="saldo-awal">Rp 0</td>
												<td>-</td>
											</tr>
											<tr style="background-color: #FFFCBE;">
												<td colspan="7" align="right"><strong>Total (Sub Total + Saldo Awal)</strong></td>
												<td id="total">Rp 0</td>
												<td class="total-pengeluaran">Rp 0</td>
											</tr>
											<tr style="background-color: #c2d2f6;">
												<td colspan="7" align="right"><strong>Saldo Akhir</strong></td>
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
													<a id="pdf-jenis-anggaran" class="btn btn-danger" target="_blank" href=""><span class="fa fa-file-pdf-o"></span> PDF Per Jenis Akun
													</a>
													<a id="excel-jenis-anggaran" class="btnCetak btn btn-success" data-judul="Detail-Jurnal-Jenis-Akun" data-type="xls" target="_blank" href=""><span class="fa fa-file-excel-o"></span> Excel Per Jenis Akun
													</a>
												</div>
											</td>
											<td>
												<div class="pull-right">
													<a id="pdf-rekap" class="btn btn-danger" target="_blank" href=""><span class="fa fa-file-pdf-o"></span> PDF Rekap Laporan
													</a>
													<a id="excel-rekap" class="btnCetak btn btn-success" data-judul="Rekap-Jurnal" data-type="xls" target="_blank" href=""><span class="fa fa-file-excel-o"></span> Excel Rekap Laporan
													</a>
												</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>

				</div>
	</section>
	<!-- /.content -->
</div>

<script>
	var unit = '';
	var tglMulai = '';
	var tglAkhir = '';

	// $(document).ready(function() {
	// 	$('#xtable').DataTable();
	// });

	function cari_data() {
		var ds = $("#ds").val();
		var de = $("#de").val();
		var majors_id = $("#majors_id").val();
		//alert(id_jurusan+id_kelas);
		if (ds != '' && de != '' && majors_id != '') {
			const baseURL = "<?php echo base_url(); ?>";
			$('#cetak-jurnal').show();
			$("#pdf-jenis-anggaran").attr("href", baseURL + "manage/report/cetak_detail_bank/" + ds + "/" + de + "/" + majors_id);
			$("#excel-jenis-anggaran").attr("href", baseURL + "manage/report/excel_detail_bank/" + ds + "/" + de + "/" + majors_id);
			$("#pdf-rekap").attr("href", baseURL + "manage/report/cetak_rekap_bank/" + ds + "/" + de + "/" + majors_id);
			$("#excel-rekap").attr("href", baseURL + "manage/report/excel_rekap_bank/" + ds + "/" + de + "/" + majors_id);

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

			var param = "<?php echo $param; ?>";
			$("#xtable").DataTable({
				ordering: true,
				processing: true,
				serverSide: true,
				destroy: true, // Menghancurkan DataTable sebelumnya sebelum inisialisasi ulang
				language: {
					processing: "<img src='<?php echo base_url() ?>uploads/loading/loading.gif' style='width:50px; height:50px;' />"
				},
				ajax: {
					url: "<?php echo base_url('manage/report/search_report_bank'); ?>",
					type: 'POST',
					data: {
						param: param,
						ds: ds,
						de: de,
						majors_id: majors_id

					},
					dataSrc: function(json) {
						$('#total-penerimaan').html("Rp " + numberFormat(json.totalPenerimaan));
						console.log(json.totalPenerimaan);
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
</script>