<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Aset Berwujud</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Data Aset</a></li>
              <li class="breadcrumb-item"><a href="<?= base_url('aset_wujud') ?>">Berwujud</a></li>
              <li class="breadcrumb-item active">Tambah Data</li>
            </ol>
          </div>
        </div>
      </div>
    </section> -->

  <div class="flash-data-gagal" data-flashdatagagal="<?= $this->session->flashdata('gagal'); ?>"></div>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="card box" style="padding: 10px;">
      <div class="card-header">
        <h3 class="card-title">Form Tambah Data</h3>

        <!-- <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
            <i class="fa fa-times"></i></button>
        </div> -->
      </div>
      <div class="card-body">
        <?php if (validation_errors()): ?>
          <div class="alert alert-danger col-md-8 alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?= validation_errors(); ?>
          </div>
        <?php endif ?>
        <p>*Keterangan Kode Aset :</p>
        <ul>
          <li>0000 = Kode Aset (0001/0002..dst) </li>
          <li>XXX = Kategori Aset (TIK,GEDUNG,dll)</li>
          <li>20XX = Tahun Perolehan Aset </li>
          <li> Template :
            <input type="text" size="30" value="0000/XXX/20XX" id="myInput">
            <button onclick="myFunction()">Salin Teks</button>
          </li>
        </ul>
        <form class="form-horizontal" action="<?= base_url('manage/easet/aset/simpanAset') ?>" autocomplete="off" method="post">
          <div class="card-body">
            <div class="form-group row">
              <label for="kode_aset" class="col-sm-2 col-form-label">Kode Aset*</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="kode_aset" placeholder="0000/XXX/20XX" required autofocus>
              </div>
            </div>
            <div class="form-group row">
              <label for="id_barang" class="col-sm-2 col-form-label">Nama Aset*</label>
              <div class="col-sm-6">
                <select name="id_barang" class="js-example-basic-single form-control" required>

                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="akun" class="col-sm-2 col-form-label">Unit*</label>
              <div class="col-sm-6">
                <select id="majorsID" style="width: 100%;" name="majors_id" class="form-control" required>
                  <option value="">--Pilih Unit--</option>
                  <?php foreach ($majors as $key => $value) { ?>
                    <option value="<?php echo $value['majors_id'] ?>"><?php echo $value['majors_short_name'] ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="akun" class="col-sm-2 col-form-label">Kas Pembelian</label>
              <div class="col-sm-6">
                <select style="width: 100%;" name="akun_kas" class="akunKas form-control" required>

                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="volume" class="col-sm-2 col-form-label">Volume</label>
              <div class="col-sm-6">
                <input type="number" class="form-control" name="volume" min="0" placeholder="Masukan Volume.." required>
              </div>
            </div>
            <div class="form-group row">
              <label for="satuan" class="col-sm-2 col-form-label">Satuan</label>
              <div class="col-sm-6">
                <select name="satuan" class="form-control" required>
                  <option value="">- Pilih --</option>
                  <option value="Buah">Buah</option>
                  <option value="Lembar">Lembar</option>
                  <option value="Unit">Unit</option>
                  <option value="Lokal">Lokal</option>
                  <option value="Cm2">Cm2</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="kondisi" class="col-sm-2 col-form-label">Kondisi</label>
              <div class="col-sm-6">
                <select name="kondisi" class="form-control" required>
                  <option value="">- Pilih --</option>
                  <option value="Baik">Baik</option>
                  <option value="Renovasi">Renovasi</option>
                  <option value="Rusak">Rusak</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="id_lokasi" class="col-sm-2 col-form-label">Lokasi Aset</label>
              <div class="col-sm-6">
                <select name="id_lokasi" class="form-control" required>
                  <option value="">- Pilih --</option>
                  <?php foreach ($lokasi as $row): ?>
                    <option value="<?= $row['id_lokasi']; ?>"><?= $row['nama_lokasi']; ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="umur_ekonomis" class="col-sm-2 col-form-label">Umur Ekonomis</label>
              <div class="col-sm-6">
                <div class="input-group mb-3">
                  <input type="number" name="umur_ekonomis" placeholder="1/2/3/.." class="form-control">
                  <div class="input-group-append">
                    <span class="input-group-text">Tahun</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label for="harga" class="col-sm-2 col-form-label">Nilai Aset</label>
              <div class="col-sm-6">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Rp.</span>
                  </div>
                  <input type="number" name="harga" class="form-control" placeholder="0000" required>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <label for="jenis_bantuan" class="col-sm-2 col-form-label">Jenis Bantuan</label>
              <div class="col-sm-6">
                <select name="jenis_bantuan" class="form-control" required>
                  <option value="">- Pilih --</option>
                  <option value="Sekolah">Sekolah</option>
                  <option value="Yayasan">Yayasan</option>
                  <option value="Pemerintah">Pemerintah</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="tanggal_terima" class="col-sm-2 col-form-label">Generate QR Code?</label>
              <div class="col-sm-6">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" name="generate" id="generate">
                  <label class="form-check-label" for="generate">
                    Ya
                  </label>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="<?= base_url('aset_wujud') ?>">
              <button type="button" class="btn btn-danger">Kembali</button>
            </a>
            <button type="submit" class="btn btn-info">Simpan</button>
          </div>
          <!-- /.card-footer -->
        </form>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">

      </div>
      <!-- /.card-footer-->
    </div>
    <!-- /.card -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
  function myFunction() {
    var copyText = document.getElementById("myInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
    alert("Teks berhasil disalin: " + copyText.value);
  }

  $(document).ready(function() {
    $('.js-example-basic-single').select2({
      theme: "classic",
      placeholder: '-- Pilih --',
      ajax: {
        url: "<?= base_url('manage/easet/aset/cariAset') ?>",
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            bar: params.term
          };
        },
        processResults: function(data) {
          var results = [];

          $.each(data, function(index, item) {
            results.push({
              id: item.id_barang,
              text: item.nama_barang
            });
          });
          return {
            results: results
          };
        }
      }
    });

    var idUnit = $("#majorsID").val();

    if (idUnit != '') {
      $('.akunKas').select2({
        theme: "classic",
        placeholder: '-- Pilih --',
        ajax: {
          url: "<?= base_url('manage/easet/aset/getListKas') ?>",
          dataType: 'json',
          delay: 250,
          data: function(params) {
            return {
              bar: params.term,
              unit: idUnit
            };
          },
          processResults: function(data) {
            var results = [];

            $.each(data, function(index, item) {
              results.push({
                id: item.account_id,
                text: item.account_code + ' - ' + item.account_description
              });
            });
            return {
              results: results
            };
          }
        }
      });
    }

  });

  $("#majorsID").change(function() {
    var idUnit = $(this).val();
    $('.akunKas').select2({
      theme: "classic",
      placeholder: '-- Pilih --',
      ajax: {
        url: "<?= base_url('manage/easet/aset/getListKas') ?>",
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            bar: params.term,
            unit: idUnit
          };
        },
        processResults: function(data) {
          var results = [];

          $.each(data, function(index, item) {
            results.push({
              id: item.account_id,
              text: item.account_code + ' - ' + item.account_description
            });
          });
          return {
            results: results
          };
        }
      }
    });
  });
</script>