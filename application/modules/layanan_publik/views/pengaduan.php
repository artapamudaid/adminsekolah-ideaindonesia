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
                          <label>Kode Pengaduan<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                          <input name="pengaduan_no" type="text" class="form-control" readonly='' placeholder="Kode Surat" value="<?php echo $pengaduan_no; ?>">
                        </div>
                        <div class="form-group">
                          <label>Status Pengadu Sebagai <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                          <select required="" name="pengaduan_nama" id="pengaduan_nama" class="form-control pengaduan_nama">
                              <option value="">-Pilih Status-</option>
                              <option value="1">Guru</option>
                              <option value="2">Siswa</option>
                              <option value="3">Alumni</option>
                              <option value="4">Publik/Umum</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Tujuan Pengaduan Kepada<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                          <select required="" name="pengaduan_tujuan" id="pengaduan_tujuan" class="form-control pengaduan_tujuan">
                            <option value="">-Pilih Status-</option>
                              <option value="1">Pelayanan</option>
                              <option value="2">Guru</option>
                              <option value="3">Lingkungan</option>
                              <option value="4">Keamanan</option>
                              <option value="5">Kesiswaan</option>
                              <option value="6">Kegiatan</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Detail Pengaduan Anda<small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                          <input name="pengaduan_detail" type="text" class="form-control" placeholder="catatan">
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
    document.querySelectorAll('.radio-control').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.value === '1') {
                document.getElementById('saran_kritik_group').style.display = 'block';
            } else {
                document.getElementById('saran_kritik_group').style.display = 'none';
            }
        });
    });
</script>