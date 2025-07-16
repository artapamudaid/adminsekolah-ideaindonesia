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
                        <center>
                          <h1>Ini Demo Mohon Maaf Tidak Bisa Input Data, Silahkan Hubungi Admin Kami...</h1> <br>
                          <div class="form-group">
                            <label>Masukkan No. Tiket Antrian <small data-toggle="tooltip" title="Wajib diisi">*</small></label>
                            <input name="tiket" type="text" class="form-control" placeholder="Isi Nomor antrian">
                          </div>
                        </center>
                        <input type="submit" class="btn btn-block btn-success" name="cari" value="Cari Tiket">
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