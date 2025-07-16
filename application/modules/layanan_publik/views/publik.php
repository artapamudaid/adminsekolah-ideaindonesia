<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="<?php echo media_url('js/jquery-migrate-3.0.0.min.js') ?>"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<section class="content">
  <div class="row">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12">
          <div class="box box-solid" style="background-color:#7EA1FF;">
            <div class="box-header text-center">
              <h1><?= $title ?></h1>
            </div>
          </div>
          <div class="box-body">
            <div class="d-flex flex-column align-items-center">
                <?php //echo form_open_multipart(current_url()); ?>
                <!-- Small boxes (Stat box) -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <!-- /.box-header -->
                      <div class="box-body">
                        
                      <h1>Ini Demo Mohon Maaf Tidak Bisa Input Data, Silahkan Hubungi Admin Kami...</h1><br>
                      <div class="form-group">
                        <label>Kode Layanan Publik<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                        <input name="publik_no" type="text" class="form-control" value="<?php echo $publik_no; ?>" readonly='' placeholder="Kode Surat">
                      </div>
                      <div class="form-group">
                        <label>Nama <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                        <input name="publik_nama" type="text" class="form-control" value="" placeholder="Nama">
                      </div>
                      <div class="form-group">
                        <label>NIK <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                        <input name="publik_nik" type="text" class="form-control" value="" placeholder="NIK">
                      </div>
                      <div class="form-group">
                        <label>Alamat <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                        <input name="publik_alamat" type="text" class="form-control" value=""  placeholder="Alamat">
                      </div>
                      <div class="form-group">
                        <label>Telepon<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                        <input name="publik_telepon" type="text" class="form-control" value="" placeholder="Telepon">
                      </div>
                      <div class="form-group">
                        <label>Bidang Yang Dituju<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                        <select name="publik_position_id" class="form-control" id="">
                          <option value="">Pilih Bidang Yang dituju</option>
                          <?php
                          foreach($position as $row):
                          ?>
                          <option value="<?php echo $row['position_id']; ?>" ><?php echo $row['position_name'] ?></option>
                          <?php
                          endforeach;
                          ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Detail Keperluan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                        <input name="keperluan" type="text" class="form-control" value="" placeholder="Detail keperluan">
                      </div>
                      
                      
                      <p class="text-muted">*) Kolom wajib diisi.</p>
                        <input type="submit" class="btn btn-block btn-success" name="simpan">
                        <a href="<?php echo site_url('layanan_publik'); ?>" class="btn btn-block btn-danger">Batal</a>
                      </div>
                      <!-- /.box-body -->
                    </div>
                  </div>
                </div><br>
                <?php //echo form_close(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

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
    $('.student_id').select2();
});
</script>