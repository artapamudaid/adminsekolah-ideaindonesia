<?php

if (isset($barang_guru)) {
    $inputBrngGuruIdValue = $barang_guru['barang_guru_id'];
    $inputNoSuratValue    = $barang_guru['barang_guru_no'];
    $inputEmployeeidValue = $barang_guru['employee_id'];
    $inputcatatanValue    = $barang_guru['catatan'];
} else {
    $inputBrngGuruIdValue = set_value('barang_guru_id');
    $inputNoSuratValue    = set_value('barang_guru_no');
    $inputEmployeeidValue = set_value('employee_id');
    $inputcatatanValue    = set_value('catatan');
}
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo isset($title) ? '' . $title : null; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('manage') ?>"><i class="fa fa-th"></i> Home</a></li>
            <li><a href="<?php echo site_url('manage/barang_guru') ?>">barang</a></li>
            <li class="active"><?php echo isset($title) ? '' . $title : null; ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php echo form_open_multipart(current_url()); ?>
        <div class="row">
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-body">
                        <?php echo validation_errors(); ?>
                        <?php if (isset($barang_guru)) { ?>
                            <input type="hidden" name="barang_guru_id" value="<?php echo $barang_guru['barang_guru_id']; ?>">
                            <input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">
                        <?php } ?>

                        <input type="hidden" name="id_users" value="<?php echo $this->session->userdata('uroleid'); ?>">

                        <div class="form-group">
                            <label>Kode Permintaan Barang<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                            <input name="barang_guru_no" type="text" class="form-control" value="<?php echo $barang_guru_no; ?>" readonly='' placeholder="Kode Permintaan barang">
                        </div>
                        <div class="form-group">
                            <label>Nama Guru/Pegawai<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                            <select required="" name="employee_id" id="employee_id" class="form-control employee_id">
                                <option value="">-Pilih Guru/Pegawai-</option>
                                <?php foreach ($employee as $row) : ?>
                                    <option value="<?php echo $row['employee_id']; ?>" <?php echo ($inputEmployeeidValue == $row['employee_id']) ? 'selected' : '' ?>><?php echo $row['employee_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Catatan/ Keterangan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                            <input name="catatan" type="text" class="form-control" value="<?php echo $inputcatatanValue ?>" placeholder="Catatan">
                        </div>
						<div class="form-group">
							<?php
							echo 'Data Permintaan Barang';
							if($inputBrngGuruIdValue != ''){
									?>
									<ol>
										<?php  
										$barang		= $inputBrngGuruIdValue;
										$data 		= $this->db->query("SELECT b.barang_name, a.jumlah FROM barang_permintaan_guru a JOIN barang b ON a.barang_id = b.barang_id WHERE a.barang_guru_id= '$barang'")->result_array();
										foreach ($data as $string) {
											echo '<li>'.$string['barang_name'].' ( '.$string['jumlah']. ')</li>';
										}
										?>
									</ol>
									<small style="color:red;"> <b>Jika Ubah data barang, maka data yang lama akan Terhapus **</b> </small>
									<?php
								}
							?>
							<div id="p_barang">
								<div class="barang_entry">
									<p>
										<label>Permintaan Barang<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
										<div class="row">
											<div class="col-md-6">
												<select required="" name="barang_id[]" class="form-control" style="width:100%;">
													<option value="">-Pilih Barang-</option>
													<?php foreach ($barang as $row) { ?>
														<option value="<?php echo $row['barang_id']; ?>"><?php echo $row['barang_name'] ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="col-md-6">
												<input name="jumlah[]" type="text" class="form-control" value="" placeholder="Jumlah Barang">
											</div>
										</div>
									</p>
								</div>
							</div>
							<h6 ><a href="#" class="btn btn-xs btn-success" id="addScnt_barang"><i class="fa fa-plus"></i><b> Tambah Baris</b></a></h6>
						</div>

                        <p class="text-muted">*) Kolom wajib diisi.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body">
                        <button type="submit" class="btn btn-block btn-success">Simpan</button>
                        <a href="<?php echo site_url('manage/barang_guru'); ?>" class="btn btn-block btn-info">Batal</a>
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </section>
</div>

<script>
$(function() {
    var scntDiv = $('#p_barang');
    var i = $('#p_barang .barang_entry').length + 1;

    $("#addScnt_barang").click(function() {
        $('<div class="barang_entry"><p><div class="row"><div class="col-md-6"><select required="" name="barang_id[]" class="form-control" style="width:100%;"><option value="">-Pilih Barang-</option><?php foreach ($barang as $row) { ?><option value="<?php echo $row['barang_id']; ?>"><?php echo $row['barang_name'] ?></option><?php } ?></select></div><div class="col-md-6"><input name="jumlah[]" type="text" class="form-control" value="" placeholder="Jumlah Barang"></div></div></p><a href="#" class="btn btn-xs btn-danger remScnt_satuan">Hapus Baris</a></div>').appendTo(scntDiv);
        i++;
        return false;
    });

    $(document).on("click", ".remScnt_satuan", function() {
        if (i > 1) {
            $(this).closest('.barang_entry').remove();
            i--;
        }
        return false;
    });
});

$(document).ready(function() {
    $('.employee_id').select2();
});

</script>
